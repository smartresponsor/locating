<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceExecutionReportInterface
{
    public function service(): string;

    public function itemCount(): int;

    /** @return array<string, ProviderGovernanceExecutionItemInterface> */
    public function providers(): array;

    /** @return array{service:string,itemCount:int,providers:array<string,array{sourceKey:string,decision:string,severity:string,acknowledgementState:string,steps:list<array{code:string,priority:string,ownerHint:string,status:string,acknowledgementRequired:bool}>}>} */
    public function toArray(): array;
}
