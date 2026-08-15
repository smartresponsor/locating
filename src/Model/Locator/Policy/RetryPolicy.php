<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Locating\Model\Locator\Policy;

use App\Locating\Service\Provider\Location\Runtime\Resilience\RetryPolicy as ServiceRetryPolicy;

/**
 * @deprecated Use App\Locating\Service\Provider\Location\Runtime\Resilience\RetryPolicy directly.
 */
final class RetryPolicy
{
    private ServiceRetryPolicy $policy;

    public function __construct(?ServiceRetryPolicy $policy = null)
    {
        $this->policy = $policy ?? new ServiceRetryPolicy();
    }

    public function shouldRetry(int $attempt, int $maxAttempt, int $statusCode): bool
    {
        return $this->policy->shouldRetry($attempt, $maxAttempt, $statusCode);
    }

    public function delayMs(int $attempt, int $baseMs = 50, int $capMs = 1000): int
    {
        return $this->policy->delayMs($attempt, $baseMs, $capMs);
    }
}
