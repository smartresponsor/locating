<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderMetricSnapshotInterface;

final class ProviderMetricSnapshot implements ProviderMetricSnapshotInterface
{
    public function __construct(
        private readonly string $operation,
        private readonly int $count,
        private readonly int $errorCount,
        private readonly float $avgMs,
        private readonly float $errorRate,
    ) {
    }

    public function operation(): string
    {
        return $this->operation;
    }

    public function count(): int
    {
        return $this->count;
    }

    public function errorCount(): int
    {
        return $this->errorCount;
    }

    public function avgMs(): float
    {
        return $this->avgMs;
    }

    public function errorRate(): float
    {
        return $this->errorRate;
    }

    public function toArray(): array
    {
        return [
            'count' => $this->count,
            'errorCount' => $this->errorCount,
            'avgMs' => $this->avgMs,
            'errorRate' => $this->errorRate,
        ];
    }
}
