<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class IdempotencyStore implements IdempotencyStoreInterface {
    /** @var array<string, int> key => expTs */
    private array $map = [];
    public function checkAndPut(string $key, int $ttlS=300): bool {
        $now=time(); $exp=$this->map[$key]??0;
        if($exp>$now){ return false; }
        $this->map[$key] = $now + max(1,$ttlS);
        return true;
    }
}
