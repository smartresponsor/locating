<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Service;

use Smartresponsor\EntityInterface\TenantContextInterface;
use Smartresponsor\InfrastructureInterface\TenantConfigRepositoryInterface;
use Smartresponsor\InfrastructureInterface\TenantUsageCounterInterface;
use Smartresponsor\ServiceInterface\AddressQuotaGuardInterface;

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
