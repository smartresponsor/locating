<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Decorator;

use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\GeoPoint;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocatorInterface;

final class RetryLocator implements LocatorInterface
{
    public function __construct(private LocatorInterface $inner, private int $retries = 2, private int $baseMs = 100)
    {
    }

    /**
     * @template T
     * @param callable():T $fn
     * @return T
     */
    private function run(callable $fn): mixed
    {
        $e = null;
        for ($i = 0; $i <= $this->retries; ++$i) {
            try {
                return $fn();
            } catch (\Throwable $e) {
                usleep(($this->baseMs * (1 << $i) + rand(0, 50)) * 1000);
            }
        }
        if ($e instanceof \Throwable) {
            throw $e;
        }

        throw new \LogicException('RetryLocator exhausted without a captured failure.');
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
