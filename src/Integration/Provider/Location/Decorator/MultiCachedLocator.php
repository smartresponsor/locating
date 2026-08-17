<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Decorator;

use App\Locating\Integration\Provider\Location\Cache\CacheInterface;
use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\GeoPoint;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocatorInterface;

final class MultiCachedLocator implements LocatorInterface
{
    private const NEG = '__NEG__';

    public function __construct(private LocatorInterface $inner, private CacheInterface $cache, private int $ttl = 600, private int $negTtl = 60)
    {
    }

    /** @param list<mixed> $a */
    private function k(string $p, array $a): string
    {
        return $p.':'.md5(json_encode($a, JSON_THROW_ON_ERROR));
    }

    /**
     * @param array<mixed,mixed> $value
     * @return array<string,mixed>
     */
    private static function stringMap(array $value): array
    {
        $map = [];
        foreach ($value as $key => $entry) {
            if (is_string($key)) {
                $map[$key] = $entry;
            }
        }

        return $map;
    }

    public function normalize(string $raw): AddressData
    {
        $k = $this->k('norm', [$raw]);
        $v = $this->cache->get($k);
        if (self::NEG === $v) {
            return new AddressData('', '', '', '', '');
        }
        if (is_array($v)) {
            return AddressData::fromArray(self::stringMap($v));
        }
        $r = $this->inner->normalize($raw);
        $arr = $r->toArray();
        if ('' === $arr['street'] && '' === $arr['city'] && '' === $arr['postalCode']) {
            $this->cache->set($k, self::NEG, $this->negTtl);
        } else {
            $this->cache->set($k, $arr, $this->ttl);
        }

        return $r;
    }

    public function geocode(AddressData $a): GeoPoint
    {
        $ka = $this->k('geo', [$a->toArray()]);
        $v = $this->cache->get($ka);
        if (self::NEG === $v) {
            return new GeoPoint(0.0, 0.0);
        }
        if (is_array($v) && is_numeric($v['lat'] ?? null) && is_numeric($v['lon'] ?? null)) {
            return new GeoPoint((float) $v['lat'], (float) $v['lon']);
        }
        $r = $this->inner->geocode($a);
        if (0.0 == $r->latitude and 0.0 == $r->longitude) {
            $this->cache->set($ka, self::NEG, $this->negTtl);
        } else {
            $this->cache->set($ka, $r->toArray(), $this->ttl);
            // обратная связка
            $kr = $this->k('rev', [$r->latitude, $r->longitude]);
            $this->cache->set($kr, $a->toArray(), $this->ttl);
        }

        return $r;
    }

    public function reverse(GeoPoint $p): AddressData
    {
        $kr = $this->k('rev', [$p->latitude, $p->longitude]);
        $v = $this->cache->get($kr);
        if (self::NEG === $v) {
            return new AddressData('', '', '', '', '');
        }
        if (is_array($v)) {
            return AddressData::fromArray(self::stringMap($v));
        }
        $r = $this->inner->reverse($p);
        $arr = $r->toArray();
        if ('' === $arr['street'] && '' === $arr['city']) {
            $this->cache->set($kr, self::NEG, $this->negTtl);
        } else {
            $this->cache->set($kr, $arr, $this->ttl);
            // обратная связка
            $kg = $this->k('geo', [$arr]);
            $this->cache->set($kg, ['lat' => $p->latitude, 'lon' => $p->longitude], $this->ttl);
        }

        return $r;
    }
}
