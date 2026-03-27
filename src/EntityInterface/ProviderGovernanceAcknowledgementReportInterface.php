<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface ProviderGovernanceAcknowledgementReportInterface
{
    public function service(): string;

    public function itemCount(): int;

    /** @return array<string, ProviderGovernanceAcknowledgementInterface> */
    public function acknowledgements(): array;

    public function toArray(): array;
}
