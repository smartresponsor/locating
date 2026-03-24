<?php

declare(strict_types=1);

namespace Smartresponsor\Integration\RateLimit;

final class TokenBucket implements RateLimiterInterface
{
    private float $tokens;
    private float $last;

    public function __construct(private int $ratePerSec)
    {
        $this->tokens = (float) $ratePerSec;
        $this->last = microtime(true);
    }

    public function acquire(): void
    {
        $now = microtime(true);
        $this->tokens = min($this->ratePerSec, $this->tokens + ($now - $this->last) * $this->ratePerSec);
        $this->last = $now;
        if ($this->tokens < 1.0) {
            $sleep = (1.0 - $this->tokens) / max(1, $this->ratePerSec);
            usleep((int) ($sleep * 1_000_000));
            $this->tokens = 0.0;
        } else {
            $this->tokens -= 1.0;
        }
    }
}
