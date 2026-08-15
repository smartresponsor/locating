<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Location\Idempotency;

use App\Locating\ServiceInterface\Location\Idempotency\IdempotencyStoreInterface;

final class IdempotencyStore implements IdempotencyStoreInterface
{
    /** @var array<string, int> key => expTs */
    private array $map = [];
    public function checkAndPut(string $key, int $ttlS = 300): bool
    {
        $now = time();
        $exp = $this->map[$key] ?? 0;
        if ($exp > $now) {
            return false;
        }
        $this->map[$key] = $now + max(1, $ttlS);
        return true;
    }
}
