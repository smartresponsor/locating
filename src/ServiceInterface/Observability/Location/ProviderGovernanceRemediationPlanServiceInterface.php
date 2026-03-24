<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Observability\Location;

use App\EntityInterface\Location\ProviderGovernanceRemediationPlanReportInterface;

interface ProviderGovernanceRemediationPlanServiceInterface
{
    public function report(): ProviderGovernanceRemediationPlanReportInterface;
}
