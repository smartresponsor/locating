<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\ProviderGovernanceAuditEntryInterface;

final class ProviderGovernanceAuditEntry implements ProviderGovernanceAuditEntryInterface
{
    /** @param list<string> $reasons @param list<string> $recommendations */
    public function __construct(
        private readonly string $sourceKey,
        private readonly string $operation,
        private readonly string $severity,
        private readonly array $reasons,
        private readonly array $recommendations,
        private readonly string $decision,
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

    public function reasons(): array
    {
        return $this->reasons;
    }

    public function recommendations(): array
    {
        return $this->recommendations;
    }

    public function decision(): string
    {
        return $this->decision;
    }

    public function toArray(): array
    {
        return [
            'sourceKey' => $this->sourceKey,
            'operation' => $this->operation,
            'severity' => $this->severity,
            'reasons' => $this->reasons,
            'recommendations' => $this->recommendations,
            'decision' => $this->decision,
        ];
    }
}
