<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface RegionTimeoutPolicyLegacyInterface
{
    public function add(string $providerId, string $region, string $op, int $timeoutMs): void;

    public function timeout(string $providerId, string $region, string $op, int $defaultMs): int;
}
