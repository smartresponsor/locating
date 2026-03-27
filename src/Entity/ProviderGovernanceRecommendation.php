<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\ProviderGovernanceRecommendationInterface;

final class ProviderGovernanceRecommendation implements ProviderGovernanceRecommendationInterface
{
    /** @param list<string> $recommendations */
    public function __construct(
        private readonly string $sourceKey,
        private readonly string $operation,
        private readonly string $severity,
        private readonly array $recommendations,
    ) {
    }

    public function sourceKey(): string
    {
        return $this->sourceKey;
    }

    public function operation(): string
    {
        return $this->operation;
    }

    public function severity(): string
    {
        return $this->severity;
    }

    public function recommendations(): array
    {
        return $this->recommendations;
    }

    public function toArray(): array
    {
        return [
            'sourceKey' => $this->sourceKey,
            'operation' => $this->operation,
            'severity' => $this->severity,
            'recommendations' => $this->recommendations,
        ];
    }
}
