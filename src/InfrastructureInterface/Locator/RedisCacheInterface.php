<?php
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface RedisCacheInterface
{
    public function get(string $key): ?string;
    public function set(string $key, string $val, int $ttl): void;
}