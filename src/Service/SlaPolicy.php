<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;

use Smartresponsor\ServiceInterface\SlaPolicyInterface;
final class SlaPolicy implements SlaPolicyInterface {
    public function weight(float $p95MsTarget, float $p95MsObserved, float $errorTarget, float $errorObserved): float {
        $latW = max(0.0, 1.0 - max(0.0, ($p95MsObserved - $p95MsTarget)) / max(1.0, $p95MsTarget));
        $errW = max(0.0, 1.0 - max(0.0, ($errorObserved - $errorTarget)) / max(0.001, $errorTarget));
        return max(0.0, min(1.0, 0.7*$latW + 0.3*$errW));
    }
}
