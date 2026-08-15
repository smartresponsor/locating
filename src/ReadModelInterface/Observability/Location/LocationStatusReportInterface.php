<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface LocationStatusReportInterface
{
    public function service(): string;

    public function status(): string;

    /**
     * @return array<string, ProviderMetricSnapshotInterface>
     */
    public function metrics(): array;

    /**
     * @return array<string, ProviderGovernanceSnapshotInterface>
     */
    public function governance(): array;

    /**
     * @return array{
     *   service:string,
     *   status:string,
     *   metrics:array<string,array{count:int,errorCount:int,avgMs:float,errorRate:float}>,
     *   governance:array<string,array{sourceKey:string,operation:string,successRate:float,ewmaMs:float,quotaAllowed:bool,unitCost:float}>
     * }
     */
    public function toArray(): array;
}
