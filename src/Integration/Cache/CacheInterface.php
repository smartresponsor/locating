<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Cache;
interface CacheInterface{
  public function get(string $key): mixed;
  public function set(string $key, mixed $value, int $ttlSec): void;
  public function delete(string $key): void;
  public function clear(): void;
  /** @return array{items:int,capacity:int,hits:int,misses:int} */
  public function stats(): array;
}
