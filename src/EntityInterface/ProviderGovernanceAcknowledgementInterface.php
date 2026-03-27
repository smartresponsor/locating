<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface ProviderGovernanceAcknowledgementInterface
{
    public function sourceKey(): string;

    public function stepCode(): string;

    public function requestedOutcome(): string;

    public function normalizedOutcome(): string;

    public function acknowledgementState(): string;

    public function accepted(): bool;

    public function note(): string;

    public function toArray(): array;
}
