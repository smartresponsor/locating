<?php

declare(strict_types=1);

namespace App\Locating\PolicyInterface\Provider\Location\Runtime\Resilience;

interface RetryPolicyInterface
{
    public function shouldRetry(int $attempt, int $maxAttempt, int $statusCode): bool;

    public function delayMs(int $attempt, int $baseMs = 50, int $capMs = 1000): int;
}
