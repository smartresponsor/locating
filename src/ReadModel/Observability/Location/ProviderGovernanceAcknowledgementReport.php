<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModel\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceAcknowledgementInterface;
use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceAcknowledgementReportInterface;

final class ProviderGovernanceAcknowledgementReport implements ProviderGovernanceAcknowledgementReportInterface
{
    /** @param array<string, ProviderGovernanceAcknowledgementInterface> $acknowledgements */
    public function __construct(private readonly string $service, private readonly array $acknowledgements)
    {
    }

    public function service(): string
    {
        return $this->service;
    }

    public function itemCount(): int
    {
        return count($this->acknowledgements);
    }

    public function acknowledgements(): array
    {
        return $this->acknowledgements;
    }

    /** @return array{service:string,itemCount:int,acknowledgements:array<string,array{sourceKey:string,stepCode:string,requestedOutcome:string,normalizedOutcome:string,acknowledgementState:string,accepted:bool,note:string}>} */
    public function toArray(): array
    {
        $acknowledgements = [];
        foreach ($this->acknowledgements as $key => $acknowledgement) {
            $acknowledgements[$key] = $acknowledgement->toArray();
        }

        return [
            'service' => $this->service,
            'itemCount' => $this->itemCount(),
            'acknowledgements' => $acknowledgements,
        ];
    }
}
