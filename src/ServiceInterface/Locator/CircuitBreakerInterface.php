<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface CircuitBreakerInterface {
    /** Report call outcome; return state: 'closed','open','half'. */
    public function onResult(string $key, bool $ok): string;
    /** Return true if call is allowed (not short-circuited). */
    public function allow(string $key): bool;
}
