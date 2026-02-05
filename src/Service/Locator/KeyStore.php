<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class KeyStore implements KeyStoreInterface {
    /** In-memory demo; replace with secure vault adapter */
    private array $map = [];
    public function get(string $providerId): ?string {
        $row = $this->map[$providerId] ?? null;
        if ($row === null) { return null; }
        $now = time();
        if (($row['start'] && $now < $row['start']) || ($row['revoke'] && $now >= $row['revoke'])) { return null; }
        return $row['key'];
    }
    public function set(string $providerId, string $key, ?int $startAtTs=null, ?int $revokeAtTs=null): void {
        $this->map[$providerId] = ['key'=>$key,'start'=>$startAtTs,'revoke'=>$revokeAtTs];
    }
}
