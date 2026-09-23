<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Decorator;

use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\GeoPoint;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocatorInterface;

final class TryFallbackLocator implements LocatorInterface
{
    public function __construct(private LocatorInterface $primary, private LocatorInterface $secondary)
    {
    }

    private function notEmpty(AddressData $a): bool
    {
        return '' !== trim($a->street)
            || '' !== trim($a->city)
            || '' !== trim($a->region)
            || '' !== trim($a->postalCode)
            || '' !== trim($a->countryCode);
    }

    private function notZero(GeoPoint $p): bool
    {
        return !$p->isZero();
    }

    public function normalize(string $raw): AddressData
    {
        $primaryFailure = null;
        try {
            $r = $this->primary->normalize($raw);
            if ($this->notEmpty($r)) {
                return $r;
            }
        } catch (\Throwable $e) {
            $primaryFailure = $e;
        }

        try {
            return $this->secondary->normalize($raw);
        } catch (\Throwable $secondaryFailure) {
            if (null !== $primaryFailure) {
                throw new \RuntimeException(
                    'Both primary and secondary locators failed during normalize; primary failure: '.$primaryFailure->getMessage(),
                    0,
                    $secondaryFailure,
                );
            }

            throw $secondaryFailure;
        }
    }

    public function geocode(AddressData $a): GeoPoint
    {
        $primaryFailure = null;
        try {
            $p = $this->primary->geocode($a);
            if ($this->notZero($p)) {
                return $p;
            }
        } catch (\Throwable $e) {
            $primaryFailure = $e;
        }

        try {
            return $this->secondary->geocode($a);
        } catch (\Throwable $secondaryFailure) {
            if (null !== $primaryFailure) {
                throw new \RuntimeException(
                    'Both primary and secondary locators failed during geocode; primary failure: '.$primaryFailure->getMessage(),
                    0,
                    $secondaryFailure,
                );
            }

            throw $secondaryFailure;
        }
    }

    public function reverse(GeoPoint $p): AddressData
    {
        $primaryFailure = null;
        try {
            $a = $this->primary->reverse($p);
            if ($this->notEmpty($a)) {
                return $a;
            }
        } catch (\Throwable $e) {
            $primaryFailure = $e;
        }

        try {
            return $this->secondary->reverse($p);
        } catch (\Throwable $secondaryFailure) {
            if (null !== $primaryFailure) {
                throw new \RuntimeException(
                    'Both primary and secondary locators failed during reverse; primary failure: '.$primaryFailure->getMessage(),
                    0,
                    $secondaryFailure,
                );
            }

            throw $secondaryFailure;
        }
    }
}
