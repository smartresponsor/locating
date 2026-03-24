<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Service\Location\CanaryGuardLegacyInterface;

final class CanaryGuard implements CanaryGuardLegacyInterface
{
    public function __construct(private float $errGate = 0.01, private float $p95Gate = 900, private float $burnGate = 1.2)
    {
    }

    public function decide(float $errorRate, float $p95Ms, float $budgetBurn): string
    {
        $bad = 0;
        if ($errorRate > $this->errGate) {
            ++$bad;
        }
        if ($p95Ms > $this->p95Gate) {
            ++$bad;
        }
        if ($budgetBurn > $this->burnGate) {
            ++$bad;
        }
        if ($bad >= 2) {
            return 'rollback';
        }
        if (1 === $bad) {
            return 'pause';
        }

        return 'continue';
    }
}
