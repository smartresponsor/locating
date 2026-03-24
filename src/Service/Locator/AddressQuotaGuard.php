<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\AddressQuotaGuardLegacyServiceInterface;
use App\Bridge\Legacy\Tenant\Location\TenantConfigLegacyRepositoryInterface;
use App\Bridge\Legacy\Tenant\Location\TenantContextLegacyInterface;
use Smartresponsor\InfrastructureInterface\Locator\TenantUsageCounterInterface;

/**
 * Guard that enforces per-tenant quotas for address related operations.
 */
final class AddressQuotaGuard implements AddressQuotaGuardLegacyServiceInterface
{
    public const OPERATION_GEOCODE = 'geocode';
    public const OPERATION_SUGGEST = 'suggest';

    public function __construct(
        private TenantContextLegacyInterface $tenantContext,
        private TenantConfigLegacyRepositoryInterface $configRepository,
        private TenantUsageCounterInterface $usageCounter,
    ) {
    }

    public function isAllowed(string $operation): bool
    {
        $tenantId = $this->tenantContext->id();

        $limit = $this->configRepository->findLimit($tenantId, $operation);
        if (null === $limit) {
            return true;
        }

        $limitPerMinute = $limit->limitPerMinute();
        if ($limitPerMinute <= 0) {
            return false;
        }

        return $this->usageCounter->increment($tenantId, $operation, $limitPerMinute);
    }
}
