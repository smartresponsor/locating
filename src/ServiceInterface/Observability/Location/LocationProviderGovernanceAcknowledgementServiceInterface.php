<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Observability\Location;

use App\Locating\ReadModelInterface\Observability\Location\ProviderGovernanceAcknowledgementReportInterface;

interface LocationProviderGovernanceAcknowledgementServiceInterface
{
    public function acknowledge(array $payload): ProviderGovernanceAcknowledgementReportInterface;
}
