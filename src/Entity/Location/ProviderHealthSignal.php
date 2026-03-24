<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\ProviderHealthSignalInterface;

final class ProviderHealthSignal implements ProviderHealthSignalInterface
{
    public function __construct(
        private readonly string $sourceKey,
        private readonly float $successRate,
        private readonly float $ewmaMs,
    ) {
    }

    public function sourceKey(): string
    {
        return $this->sourceKey;
    }

    public function successRate(): float
    {
        return $this->successRate;
    }

    public function ewmaMs(): float
    {
        return $this->ewmaMs;
    }

    public function toArray(): array
    {
        return [
            'sourceKey' => $this->sourceKey,
            'successRate' => $this->successRate,
            'ewmaMs' => $this->ewmaMs,
        ];
    }
}
