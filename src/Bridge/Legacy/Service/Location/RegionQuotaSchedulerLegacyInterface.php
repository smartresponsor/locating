<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface RegionQuotaSchedulerLegacyInterface
{
    public function split(int $total, array $weight): array;

    public function consume(string $region): bool;

    public function reset(): void;
}
