<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface CircuitBreakerInterface
{
    /** Report call outcome; return state: 'closed','open','half'. */
    public function onResult(string $key, bool $ok): string;
    /** Return true if call is allowed (not short-circuited). */
    public function allow(string $key): bool;
}
