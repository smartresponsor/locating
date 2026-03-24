<?php

declare(strict_types=1);

namespace Smartresponsor\Integration\Locator\Decorator;

use Smartresponsor\Model\Locator\AddressData;
use Smartresponsor\Model\Locator\GeoPoint;

final class TryFallbackLocator implements LocatorInterface
{
    public function __construct(private LocatorInterface $primary, private LocatorInterface $secondary)
    {
    }

    private function notEmpty(AddressData $a): bool
    {
        return !$a->isEmpty();
    }

    private function notZero(GeoPoint $p): bool
    {
        return !$p->isZero();
    }

    public function normalize(string $raw): AddressData
    {
        try {
            $r = $this->primary->normalize($raw);
            if ($this->notEmpty($r)) {
                return $r;
            }
        } catch (\Throwable $e) {
        }

        return $this->secondary->normalize($raw);
    }

    public function geocode(AddressData $a): GeoPoint
    {
        try {
            $p = $this->primary->geocode($a);
            if ($this->notZero($p)) {
                return $p;
            }
        } catch (\Throwable $e) {
        }

        return $this->secondary->geocode($a);
    }

    public function reverse(GeoPoint $p): AddressData
    {
        try {
            $a = $this->primary->reverse($p);
            if ($this->notEmpty($a)) {
                return $a;
            }
        } catch (\Throwable $e) {
        }

        return $this->secondary->reverse($p);
    }
}
