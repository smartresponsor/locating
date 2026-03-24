<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface ProviderGovernanceAuditEntryInterface
{
    public function sourceKey(): string;

    public function operation(): string;

    public function severity(): string;

    /** @return list<string> */
    public function reasons(): array;

    /** @return list<string> */
    public function recommendations(): array;

    public function decision(): string;

    public function toArray(): array;
}
