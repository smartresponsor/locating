<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface FakeRedisInterface
{
    public function incrby(string $key, int $n): int;
    public function get(string $key): ?string;
    public function expire(string $key, int $ttl): void;
    public function pttl(string $key): int;
    public function del(string $key): void;
}