<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class RateLimiterMemory implements RateLimiterInterface {
    /** @var array<string, array{start:int,count:int}> */
    private array $state = [];
    public function allow(string $key, int $limit, int $windowMs): bool {
        $now = (int)floor(microtime(true)*1000);
        $row = $this->state[$key] ?? ['start'=>$now,'count'=>0];
        if ($now - $row['start'] >= $windowMs) {
            $row = ['start'=>$now,'count'=>0];
        }
        if ($row['count'] >= $limit) {
            $this->state[$key] = $row;
            return false;
        }
        $row['count']++;
        $this->state[$key] = $row;
        return true;
    }
}
