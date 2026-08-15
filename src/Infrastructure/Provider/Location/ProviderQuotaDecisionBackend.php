<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location;

use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderQuotaDecisionBackendInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantQuotaManagerInterface;

final class ProviderQuotaDecisionBackend implements ProviderQuotaDecisionBackendInterface
{
    public function __construct(private readonly TenantQuotaManagerInterface $quotaManager)
    {
    }

    public function allow(string $tenantId, string $operation, int $units = 1, bool $record = false): bool
    {
        return $this->quotaManager->allow($tenantId, $operation, $units, $record);
    }
}
