<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Contract;
interface CacheInterface{
    public function get(string $key, mixed $default=null);
    public function set(string $key, mixed $value, int $ttlSeconds=300): void;
    public function delete(string $key): void;
    public function clear(): void;
}
