<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class CanaryGuard implements CanaryGuardInterface {
    public function __construct(private float $errGate=0.01, private float $p95Gate=900, private float $burnGate=1.2){}
    public function decide(float $errorRate, float $p95Ms, float $budgetBurn): string {
        $bad = 0;
        if ($errorRate > $this->errGate) { $bad++; }
        if ($p95Ms > $this->p95Gate) { $bad++; }
        if ($budgetBurn > $this->burnGate) { $bad++; }
        if ($bad >= 2) { return 'rollback'; }
        if ($bad === 1) { return 'pause'; }
        return 'continue';
    }
}
