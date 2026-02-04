<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service\Locator;
final class RetryPolicy {
    public function shouldRetry(int $attempt, int $maxAttempt, int $statusCode): bool {
        if ($attempt >= $maxAttempt) { return false; }
        return in_array($statusCode, [408,429,500,502,503,504], true);
    }
    /** Full-jitter backoff with cap */
    public function delayMs(int $attempt, int $baseMs=50, int $capMs=1000): int {
        $exp = min($capMs, $baseMs * (1 << max(0, min(10,$attempt))));
        return random_int(0, $exp);
    }
}
