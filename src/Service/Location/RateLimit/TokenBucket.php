<?php

declare(strict_types=1);

namespace App\Locating\Service\Location\RateLimit;

use App\Locating\ServiceInterface\Location\RateLimit\TokenBucketInterface;

final class TokenBucket implements TokenBucketInterface
{
    private string $file;
    private int $capacity;
    private float $rate;

    public function __construct(string $nameEntity, int $capacity = 10, float $rate = 1.0)
    {
        $this->file = sys_get_temp_dir().'/sr_tb_'.preg_replace('/[^a-z0-9_\-]/i', '_', $nameEntity).'.json';
        $this->capacity = $capacity;
        $this->rate = $rate;
    }

    public function allow(): bool
    {
        $now = microtime(true);
        $state = ['tokens' => (float) $this->capacity, 'ts' => $now];
        if (is_file($this->file)) {
            $decoded = json_decode((string) file_get_contents($this->file), true);
            if (is_array($decoded)) {
                $state = [
                    'tokens' => is_numeric($decoded['tokens'] ?? null) ? (float) $decoded['tokens'] : (float) $this->capacity,
                    'ts' => is_numeric($decoded['ts'] ?? null) ? (float) $decoded['ts'] : $now,
                ];
            }
        }
        $elapsed = max(0.0, $now - $state['ts']);
        $state['tokens'] = min((float) $this->capacity, $state['tokens'] + $elapsed * $this->rate);
        $state['ts'] = $now;
        if ($state['tokens'] < 1.0) {
            file_put_contents($this->file, json_encode($state, JSON_THROW_ON_ERROR));

            return false;
        }
        $state['tokens'] -= 1.0;
        file_put_contents($this->file, json_encode($state, JSON_THROW_ON_ERROR));

        return true;
    }
}
