<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceRecommendationReportInterface
{
    public function service(): string;

    public function itemCount(): int;

    /** @return array<string, ProviderGovernanceRecommendationInterface> */
    public function providers(): array;

    /** @return array{service:string,itemCount:int,providers:array<string,array{sourceKey:string,operation:string,severity:string,recommendations:list<string>}>} */
    public function toArray(): array;
}
