<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Decorator;

use App\Locating\Integration\Provider\Location\Cache\SimpleArrayCache;
use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\GeoPoint;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocatorInterface;

final class CachedLocator implements LocatorInterface
{
    public function __construct(private LocatorInterface $inner, private SimpleArrayCache $cache, private int $ttl = 600)
    {
    }

    private function key(string $p, string $s): string
    {
        return 'locator:'.$p.':'.sha1($s);
    }

    public function normalize(string $raw): AddressData
    {
        $k = $this->key('n', $raw);
        $h = $this->cache->get($k);
        if ($h instanceof AddressData) {
            return $h;
        }
        $r = $this->inner->normalize($raw);
        $this->cache->set($k, $r, $this->ttl);

        return $r;
    }

    public function geocode(AddressData $a): GeoPoint
    {
        $payload = implode('|', [$a->street, $a->city, $a->region, $a->postalCode, $a->countryCode]);
        $k = $this->key('g', $payload);
        $h = $this->cache->get($k);
        if ($h instanceof GeoPoint) {
            return $h;
        }
        $r = $this->inner->geocode($a);
        $this->cache->set($k, $r, $this->ttl);

        return $r;
    }

    public function reverse(GeoPoint $p): AddressData
    {
        $k = $this->key('r', "{$p->latitude},{$p->longitude}");
        $h = $this->cache->get($k);
        if ($h instanceof AddressData) {
            return $h;
        }
        $r = $this->inner->reverse($p);
        $this->cache->set($k, $r, $this->ttl);

        return $r;
    }
}
