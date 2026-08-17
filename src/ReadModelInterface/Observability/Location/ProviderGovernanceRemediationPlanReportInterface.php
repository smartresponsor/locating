<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceRemediationPlanReportInterface
{
    public function service(): string;

    public function itemCount(): int;

    /** @return array<string, ProviderGovernanceRemediationPlanInterface> */
    public function providers(): array;

    /** @return array{service:string,itemCount:int,providers:array<string,array{sourceKey:string,operation:string,severity:string,decision:string,reasons:list<string>,recommendations:list<string>,steps:list<array{code:string,priority:string,summary:string,ownerHint:string}>}>} */
    public function toArray(): array;
}
