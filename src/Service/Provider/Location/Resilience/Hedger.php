<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */

namespace App\Locating\Service\Provider\Location\Resilience;

class Hedger
{
    /**
     * @template T
     * @param list<callable(): array<T>> $callables
     * @return array<T>
     */
    public static function race(array $callables, int $hedgeDelayMs): array
    {
        // Simple sequential hedging: start first, after delay start second; return first successful
        $start = microtime(true);
        $res1 = null;
        $err1 = null;
        try {
            $res1 = $callables[0]();
        } catch (\Throwable $e) {
            $err1 = $e;
        }
        $elapsed = (int)((microtime(true) - $start) * 1000);
        if ($res1) {
            return $res1;
        }
        if ($elapsed < $hedgeDelayMs && isset($callables[1])) {
            usleep(($hedgeDelayMs - $elapsed) * 1000);
        }
        $err2 = null;
        if (isset($callables[1])) {
            try {
                $res2 = $callables[1]();
                if ($res2) {
                    return $res2;
                }
            } catch (\Throwable $e) {
                $err2 = $e;
            }
        }

        if (null !== $err1 && null !== $err2) {
            throw new \RuntimeException(
                'Both hedged callables failed; primary failure: '.$err1->getMessage(),
                0,
                $err2,
            );
        }
        if (null !== $err1) {
            throw $err1;
        }
        if (null !== $err2) {
            throw $err2;
        }

        return [];
    }
}
