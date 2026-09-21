<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\ServiceInterface\Address\Location\AddressQuotaGuardServiceInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantConfigRepositoryInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantContextInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantUsageCounterInterface;

/**
 * Guard that enforces per-tenant quotas for address related operations.
 */
final class AddressQuotaGuard implements AddressQuotaGuardServiceInterface
{
    public const OPERATION_GEOCODE = 'geocode';
    public const OPERATION_SUGGEST = 'suggest';

    public function __construct(
        private TenantContextInterface $tenantContext,
        private TenantConfigRepositoryInterface $configRepository,
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
