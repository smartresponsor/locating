<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface;

/**
 */

interface RedisCacheInterface
{
    public function get(string $key): ?string;
    public function set(string $key, string $val, int $ttl): void;
}