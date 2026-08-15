<?php

declare(strict_types=1);

namespace App\Locating\Contract\Location;

interface LocationMetricsContract
{
    /** @param array<string,string> $labels */
    public function inc(string $nameEntity, array $labels = []): void;

    /** @param array<string,string> $labels */
    public function observeMs(string $nameEntity, float $ms, array $labels = []): void;

    /** @return array<string,mixed> */
    public function snapshot(): array;
}
