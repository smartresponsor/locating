<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Contract\Cache;
interface CacheInterface{
  public function get(string $key, mixed $default=null): mixed;
  public function set(string $key, mixed $value, int $ttl=300): bool;
  public function delete(string $key): bool;
  public function clear(): bool;
  public function has(string $key): bool;
}
