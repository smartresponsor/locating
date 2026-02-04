<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Cache;
use Redis;
use SmartResponsor\Contract\Cache\CacheInterface;
final class RedisCache implements CacheInterface{
  public function __construct(private Redis $r){}
  public function get(string $key, mixed $default=null): mixed{
    $v = $this->r->get($key);
    if ($v === false) return $default;
    return unserialize((string)$v);
  }
  public function set(string $key, mixed $value, int $ttl=300): bool{
    $payload = serialize($value);
    if ($ttl > 0) return (bool)$this->r->setex($key, $ttl, $payload);
    return (bool)$this->r->set($key, $payload);
  }
  public function delete(string $key): bool{ return (bool)$this->r->del($key); }
  public function clear(): bool{ $this->r->flushDB(); return true; }
  public function has(string $key): bool{ return $this->r->exists($key) > 0; }
}
