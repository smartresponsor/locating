<?php

declare(strict_types=1);

namespace App\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\ProviderQuotaDecisionLegacyManagerInterface;
use App\InfrastructureInterface\Provider\Location\ProviderQuotaDecisionBackendInterface;

final class SmartresponsorProviderQuotaDecisionBackend implements ProviderQuotaDecisionBackendInterface
{
    public function __construct(private readonly ProviderQuotaDecisionLegacyManagerInterface $quotaManager)
    {
    }

    public function allow(string $tenantId, string $operation, int $units = 1, bool $record = false): bool
    {
        return $this->quotaManager->allow($tenantId, $operation, $units, $record);
    }
}
