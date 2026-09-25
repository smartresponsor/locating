<?php

declare(strict_types=1);

namespace App\Locating\PolicyInterface\Provider\Location\Runtime\Resilience;

interface RegionTimeoutPolicyInterface
{
    public function add(string $providerId, string $region, string $op, int $timeoutMs): void;

    public function timeout(string $providerId, string $region, string $op, int $defaultMs): int;
}
