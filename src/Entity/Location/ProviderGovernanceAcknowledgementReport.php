<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\ProviderGovernanceAcknowledgementInterface;
use App\EntityInterface\Location\ProviderGovernanceAcknowledgementReportInterface;

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
