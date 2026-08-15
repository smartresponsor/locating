<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceReportInterface
{
    public function service(): string;

    public function providerCount(): int;

    public function degradedCount(): int;

    /**
     * @return array<string, ProviderGovernanceSnapshotInterface>
     */
    public function providers(): array;

    /**
     * @return array{
     *   service:string,
     *   providerCount:int,
     *   degradedCount:int,
     *   providers:array<string,array{sourceKey:string,operation:string,successRate:float,ewmaMs:float,quotaAllowed:bool,unitCost:float}>
     * }
     */
    public function toArray(): array;
}
