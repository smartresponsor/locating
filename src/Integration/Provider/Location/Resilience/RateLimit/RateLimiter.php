<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Resilience\RateLimit;

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

    /** @return array{allowed:bool, remaining:int, reset:int} */
    public function allow(string $key, ?int $now = null): array
    {
        $now = $now ?? time();
        $path = $this->path($key);
        $state = ['ts' => $now, 'tokens' => $this->limit + $this->burst];

        if (file_exists($path)) {
            $decoded = json_decode((string) file_get_contents($path), true);
            if (is_array($decoded)) {
                $state = [
                    'ts' => is_numeric($decoded['ts'] ?? null) ? (int) $decoded['ts'] : $now,
                    'tokens' => is_numeric($decoded['tokens'] ?? null) ? (int) $decoded['tokens'] : $this->limit + $this->burst,
                ];
            }
            $elapsed = max(0, $now - $state['ts']);
            $refill = (int) floor($elapsed * ($this->limit / $this->windowSec));
            $state['tokens'] = min($this->limit + $this->burst, $state['tokens'] + $refill);
            $state['ts'] = $now;
        }

        $allowed = false;
        if ((int)$state['tokens'] > 0) {
            $state['tokens'] = (int)$state['tokens'] - 1;
            $allowed = true;
        }

        file_put_contents($path, json_encode($state, JSON_THROW_ON_ERROR));

        return [
            'allowed' => $allowed,
            'remaining' => (int)$state['tokens'],
            'reset' => $now + $this->windowSec,
        ];
    }
}
