<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderGovernanceAcknowledgementInterface
{
    public function sourceKey(): string;

    public function stepCode(): string;

    public function requestedOutcome(): string;

    public function normalizedOutcome(): string;

    public function acknowledgementState(): string;

    public function accepted(): bool;

    public function note(): string;

    /** @return array{sourceKey:string,stepCode:string,requestedOutcome:string,normalizedOutcome:string,acknowledgementState:string,accepted:bool,note:string} */
    public function toArray(): array;
}
