<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceExecutionItemInterface
{
    public function sourceKey(): string;

    public function decision(): string;

    public function severity(): string;

    public function acknowledgementState(): string;

    /** @return list<ProviderGovernanceExecutionStepStatusInterface> */
    public function steps(): array;

    /** @return array{sourceKey:string,decision:string,severity:string,acknowledgementState:string,steps:list<array{code:string,priority:string,ownerHint:string,status:string,acknowledgementRequired:bool}>} */
    public function toArray(): array;
}
