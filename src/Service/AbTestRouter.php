<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class AbTestRouter implements AbTestRouterInterface {
    public function decide(string $tenantId, string $op, array $variant, float $ratioB): string {
        $a = (string)($variant['A'] ?? '');
        $b = (string)($variant['B'] ?? '');
        if ($a==='' || $b==='') { return $a ?: $b; }
        $seed = crc32($tenantId.'|'.$op);
        $p = ($seed % 1000) / 1000.0;
        return ($p < max(0.0, min(1.0, $ratioB))) ? $b : $a;
    }
}
