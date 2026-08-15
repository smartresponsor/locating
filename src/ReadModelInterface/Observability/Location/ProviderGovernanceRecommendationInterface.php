<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceRecommendationInterface
{
    public function sourceKey(): string;

    public function operation(): string;

    public function severity(): string;

    /** @return list<string> */
    public function recommendations(): array;

    public function toArray(): array;
}
