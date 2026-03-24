<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Service;

final class RetryPolicy
{
    /** @var list<int> */
    private const RETRIABLE_STATUS_CODE_LIST = [408, 429, 500, 502, 503, 504];

    /**
     * Retry transient transport/provider failures while max attempts are not reached.
     */
    public function shouldRetry(int $attempt, int $maxAttempt, int $statusCode): bool
    {
        if ($attempt >= $maxAttempt) {
            return false;
        }

        return in_array($statusCode, self::RETRIABLE_STATUS_CODE_LIST, true);
    }

    /**
     * Full-jitter exponential backoff with cap.
     */
    public function delayMs(int $attempt, int $baseMs = 50, int $capMs = 1000): int
    {
        $exponent = max(0, min(10, $attempt));
        $maxDelay = min($capMs, $baseMs * (1 << $exponent));

        return random_int(0, $maxDelay);
    }
}
