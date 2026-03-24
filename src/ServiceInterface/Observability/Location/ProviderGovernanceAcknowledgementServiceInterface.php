<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Observability\Location;

use App\EntityInterface\Location\ProviderGovernanceAcknowledgementReportInterface;

interface ProviderGovernanceAcknowledgementServiceInterface
{
    public function acknowledge(array $payload): ProviderGovernanceAcknowledgementReportInterface;
}
