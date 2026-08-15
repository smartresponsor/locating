<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceSnapshotInterface
{
    public function sourceKey(): string;

    public function operation(): string;

    public function successRate(): float;

    public function ewmaMs(): float;

    public function quotaAllowed(): bool;

    public function unitCost(): float;

    public function toArray(): array;
}
