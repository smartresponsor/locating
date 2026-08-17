<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceRemediationPlanInterface
{
    public function sourceKey(): string;

    public function operation(): string;

    public function severity(): string;

    public function decision(): string;

    /** @return list<string> */
    public function reasons(): array;

    /** @return list<string> */
    public function recommendations(): array;

    /** @return list<ProviderGovernanceRemediationStepInterface> */
    public function steps(): array;

    /** @return array{sourceKey:string,operation:string,severity:string,decision:string,reasons:list<string>,recommendations:list<string>,steps:list<array{code:string,priority:string,summary:string,ownerHint:string}>} */
    public function toArray(): array;
}
