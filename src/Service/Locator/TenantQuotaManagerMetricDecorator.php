<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Provider\Location\LocationMetricLegacyRecorderInterface;
use App\Bridge\Legacy\Provider\Location\ProviderQuotaDecisionLegacyManagerInterface;

/**
 * Decorator for TenantQuotaManagerInterface that records basic counters
 * for allow and deny decisions per operation.
 */
final class TenantQuotaManagerMetricDecorator implements ProviderQuotaDecisionLegacyManagerInterface
{
    private ProviderQuotaDecisionLegacyManagerInterface $inner;

    private LocationMetricLegacyRecorderInterface $metricRecorder;

    private string $metricPrefix;

    public function __construct(
        ProviderQuotaDecisionLegacyManagerInterface $inner,
        LocationMetricLegacyRecorderInterface $metricRecorder,
        string $metricPrefix = 'tenant_quota',
    ) {
        $this->inner = $inner;
        $this->metricRecorder = $metricRecorder;
        $this->metricPrefix = $metricPrefix;
    }

    public function allow(string $tenantId, string $op, int $unit = 1, bool $consume = true): bool
    {
        $ok = $this->inner->allow($tenantId, $op, $unit, $consume);

        $key = $this->metricPrefix.'_'.$op;
        $this->metricRecorder->incrementCounter($key, $ok ? 'ok' : 'error');

        return $ok;
    }

    public function remaining(string $tenantId, string $op): int
    {
        return $this->inner->remaining($tenantId, $op);
    }
}
