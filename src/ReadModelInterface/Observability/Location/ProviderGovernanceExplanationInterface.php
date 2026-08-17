<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceExplanationInterface
{
    public function sourceKey(): string;

    public function operation(): string;

    public function severity(): string;

    /** @return list<string> */
    public function reasons(): array;

    /** @return array{sourceKey:string,operation:string,severity:string,reasons:list<string>} */
    public function toArray(): array;
}
