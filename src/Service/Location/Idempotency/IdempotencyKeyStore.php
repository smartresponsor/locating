<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Location\Idempotency;

use App\Locating\ServiceInterface\Location\Idempotency\IdempotencyKeyStoreInterface;

final class IdempotencyKeyStore implements IdempotencyKeyStoreInterface
{
    /** @var array<string,int> key=>expireTs */
    private array $m = [];
    public function accept(string $key, int $ttlS): bool
    {
        $now = time();
        $exp = $this->m[$key] ?? 0;
        if ($exp > $now) {
            return false;
        }
        $this->m[$key] = $now + max(1, $ttlS);
        return true;
    }
    public function purge(): void
    {
        $now = time();
        foreach ($this->m as $k => $e) {
            if ($e <= $now) {
                unset($this->m[$k]);
            }
        }
    }
}
