<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Resilience;

final class Retry
{
    public static function withBackoff(callable $fn, int $attempts = 3, int $baseMs = 100): mixed
    {
        $e = null;
        for ($i = 0;$i < $attempts;$i++) {
            try {
                return $fn();
            } catch (\Throwable $ex) {
                $e = $ex;
                usleep(($baseMs * (2 ** $i) + rand(0, 50)) * 1000);
            }
        }
        throw $e ?? new \RuntimeException('failed');
    }
}
