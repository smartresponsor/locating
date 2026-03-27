<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Service;

use App\EntityInterface\TenantContextInterface;
use App\InfrastructureInterface\TenantConfigRepositoryInterface;
use App\InfrastructureInterface\TenantUsageCounterInterface;
use App\ServiceInterface\AddressQuotaGuardInterface;

/**
 * Guard that enforces per-tenant quotas for address related operations.
 */
final class AddressQuotaGuard implements AddressQuotaGuardInterface
{
    public const OPERATION_GEOCODE = 'geocode';
    public const OPERATION_SUGGEST = 'suggest';

    public function __construct(
        private TenantContextInterface $tenantContext,
        private TenantConfigRepositoryInterface $configRepository,
        private TenantUsageCounterInterface $usageCounter
    ) {
    }

    public function isAllowed(string $operation): bool
    {
        $tenantId = $this->tenantContext->id();

        $limit = $this->configRepository->findLimit($tenantId, $operation);
        if ($limit === null) {
            return true;
        }

        $limitPerMinute = $limit->limitPerMinute();
        if ($limitPerMinute <= 0) {
            return false;
        }

        return $this->usageCounter->increment($tenantId, $operation, $limitPerMinute);
    }
}
