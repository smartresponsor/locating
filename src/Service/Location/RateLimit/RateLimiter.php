<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Location\RateLimit;

use App\Locating\ServiceInterface\Location\RateLimit\RateLimiterInterface;

final class RateLimiter implements RateLimiterInterface
{
    /** @var array<string, array{tokens:float, ts:int, burst:int, rate:int}> */
    private array $bucket = [];
    public function allow(string $key, int $tokens = 1, int $refillRate = 10, int $burst = 20): bool
    {
        $now = time();
        $b = $this->bucket[$key] ?? ['tokens' => $burst, 'ts' => $now, 'burst' => $burst, 'rate' => $refillRate];
        $elapsed = max(0, $now - (int)$b['ts']);
        $b['tokens'] = min($b['burst'], $b['tokens'] + $elapsed * $b['rate']);
        $b['ts'] = $now;
        if ($b['tokens'] >= $tokens) {
            $b['tokens'] -= $tokens;
            $this->bucket[$key] = $b;
            return true;
        }
        $this->bucket[$key] = $b;
        return false;
    }
}
