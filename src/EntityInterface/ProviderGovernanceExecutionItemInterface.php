<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface ProviderGovernanceExecutionItemInterface
{
    public function sourceKey(): string;

    public function decision(): string;

    public function severity(): string;

    public function acknowledgementState(): string;

    public function steps(): array;

    public function toArray(): array;
}
