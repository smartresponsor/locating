<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class TenantSeparator implements TenantSeparatorInterface {
    public function schema(string $tenantId): string {
        $s = strtolower(preg_replace('/[^a-z0-9_]/i', '_', $tenantId));
        $s = preg_replace('/_+/', '_', $s);
        $s = trim($s, '_');
        if ($s === '') { $s = 'default'; }
        if (!preg_match('/^[a-z]/', $s)) { $s = 't_'.$s; }
        return 't_'.$s;
    }
    public function storageKey(string $tenantId, string $baseKey): string {
        $s = $this->schema($tenantId);
        return $s.':'.trim($baseKey);
    }
}
