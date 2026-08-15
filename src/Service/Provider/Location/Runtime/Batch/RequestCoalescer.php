<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Batch;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Batch\RequestCoalescerInterface;

final class RequestCoalescer implements RequestCoalescerInterface
{
    /** @var array<string, array{val:array, exp:int}> */
    private array $memo = [];
    /** @var array<string, bool> */
    private array $lock = [];
    public function resolve(string $key, callable $resolver, int $ttlS = 60): array
    {
        $now = time();
        $row = $this->memo[$key] ?? null;
        if ($row !== null && $row['exp'] > $now) {
            return $row['val'];
        }
        // crude lock for same-process coalescing
        while (($this->lock[$key] ?? false) === true) {
            usleep(1000);
            $row = $this->memo[$key] ?? null;
            if ($row && $row['exp'] > $now) {
                return $row['val'];
            }
        }
        $this->lock[$key] = true;
        try {
            $val = $resolver($key);
            if (!\is_array($val)) {
                throw new \RuntimeException('Resolver must return array');
            }
            $this->memo[$key] = ['val' => $val,'exp' => $now + max(1, $ttlS)];
            return $val;
        } finally {
            unset($this->lock[$key]);
        }
    }
}
