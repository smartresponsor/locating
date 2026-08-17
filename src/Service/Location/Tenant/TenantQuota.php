<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Location\Tenant;

use App\Locating\ServiceInterface\Location\Tenant\TenantQuotaInterface;

final class TenantQuota implements TenantQuotaInterface
{
    /** @var array<string, array<string, array{base:int, adaptive:int, used:int, ewmaError:float}>> */
    private array $map = [];

    public function __construct(private float $alpha = 0.3, private float $floorRatio = 0.5, private float $ceilRatio = 1.5)
    {
    }

    public function setBase(string $tenantId, string $op, int $base): void
    {
        $value = max(1, $base);
        $current = $this->map[$tenantId][$op] ?? ['base' => $value, 'adaptive' => $value, 'used' => 0, 'ewmaError' => 0.0];
        $current['base'] = $value;
        $current['adaptive'] = $value;
        $this->map[$tenantId][$op] = $current;
    }

    public function limit(string $tenantId, string $op): int
    {
        $t = $this->map[$tenantId][$op] ?? ['base' => 100, 'adaptive' => 100, 'used' => 0, 'ewmaError' => 0.0];

        return max(1, (int) $t['adaptive']);
    }

    public function update(string $tenantId, string $op, int $used, float $errorRate): int
    {
        $current = $this->map[$tenantId][$op] ?? ['base' => 100, 'adaptive' => 100, 'used' => 0, 'ewmaError' => 0.0];
        $current['used'] = max(0, $used);
        $current['ewmaError'] = (1 - $this->alpha) * $current['ewmaError'] + $this->alpha * max(0.0, min(1.0, $errorRate));
        $load = ($current['used'] + 1.0) / max(1.0, (float) $current['adaptive']);
        $factor = 1.0;
        if ($current['ewmaError'] > 0.1) {
            $factor *= 0.85;
        }
        if ($load > 0.9) {
            $factor *= 0.9;
        }
        if ($load < 0.6 && $current['ewmaError'] < 0.05) {
            $factor *= 1.15;
        }
        $base = (float) $current['base'];
        $new = (int) round(min($this->ceilRatio * $base, max($this->floorRatio * $base, $current['adaptive'] * $factor)));
        $current['adaptive'] = max(1, $new);
        $this->map[$tenantId][$op] = $current;

        return $current['adaptive'];
    }
}
