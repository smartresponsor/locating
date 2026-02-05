<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Integration\Locator\RateLimit;

final class RateLimiter
{
    private string $dir;

    public function __construct(private int $limit, private int $windowSec, private int $burst)
    {
        $this->dir = sys_get_temp_dir() . '/locator_rl';
        if (!is_dir($this->dir)) {
            @mkdir($this->dir, 0755, true);
        }
    }

    private function path(string $key): string
    {
        return $this->dir . '/' . sha1($key) . '.json';
    }

    public function allow(string $key, ?int $now = null): array
    {
        $now = $now ?? time();
        $path = $this->path($key);
        $state = ['ts' => $now, 'tokens' => $this->limit + $this->burst];

        if (file_exists($path)) {
            $state = json_decode((string)file_get_contents($path), true) ?: $state;
            $elapsed = max(0, $now - (int)$state['ts']);
            $refill = (int)floor($elapsed * ($this->limit / $this->windowSec));
            $state['tokens'] = min($this->limit + $this->burst, (int)$state['tokens'] + $refill);
            $state['ts'] = $now;
        }

        $allowed = false;
        if ((int)$state['tokens'] > 0) {
            $state['tokens'] = (int)$state['tokens'] - 1;
            $allowed = true;
        }

        file_put_contents($path, (string)json_encode($state));

        return [
            'allowed' => $allowed,
            'remaining' => (int)$state['tokens'],
            'reset' => $now + $this->windowSec,
        ];
    }
}
