<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Decorator;

use App\Locating\Integration\Provider\Location\Metrics\HealthMetrics;
use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\GeoPoint;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocatorInterface;

final class HealthProbeLocator implements LocatorInterface
{
    public function __construct(private LocatorInterface $inner, private HealthMetrics $m)
    {
    }

    /**
     * @template T
     * @param callable():T $fn
     * @return T
     */
    private function run(callable $fn): mixed
    {
        $t = microtime(true);
        try {
            $res = $fn();
            $this->m->record(microtime(true) - $t, true);

            return $res;
        } catch (\Throwable $e) {
            $this->m->record(microtime(true) - $t, false);
            throw $e;
        }
    }

    public function normalize(string $raw): AddressData
    {
        return $this->run(fn () => $this->inner->normalize($raw));
    }

    public function geocode(AddressData $a): GeoPoint
    {
        return $this->run(fn () => $this->inner->geocode($a));
    }

    public function reverse(GeoPoint $p): AddressData
    {
        return $this->run(fn () => $this->inner->reverse($p));
    }
}
