<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface RegionQuotaSchedulerInterface
{
    public function split(int $total, array $weight): array;

    public function consume(string $region): bool;

    public function reset(): void;
}
