<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceAcknowledgementReportInterface
{
    public function service(): string;

    public function itemCount(): int;

    /** @return array<string, ProviderGovernanceAcknowledgementInterface> */
    public function acknowledgements(): array;

    /** @return array{service:string,itemCount:int,acknowledgements:array<string,array{sourceKey:string,stepCode:string,requestedOutcome:string,normalizedOutcome:string,acknowledgementState:string,accepted:bool,note:string}>} */
    public function toArray(): array;
}
