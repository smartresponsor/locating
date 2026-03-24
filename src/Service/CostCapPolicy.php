<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class CostCapPolicy implements CostCapPolicyInterface {
    public function allow(float $unitCost, float $cap): bool {
        return $unitCost <= max(0.0, $cap);
    }
}
