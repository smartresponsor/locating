<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Cache;

use App\Locating\Contract\Cache\CacheInterface;
use JsonException;
use Redis;

final class RedisCache implements CacheInterface
{
    public function __construct(private Redis $r)
    {
    }
    public function get(string $key, mixed $default = null): mixed
    {
        $value = $this->r->get($key);
        if (!is_string($value)) {
            return $default;
        }

        try {
            return json_decode($value, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return $default;
        }
    }
    public function set(string $key, mixed $value, int $ttl = 300): bool
    {
        try {
            $payload = json_encode($value, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            return false;
        }
        if ($ttl > 0) {
            return (bool)$this->r->setex($key, $ttl, $payload);
        }
        return (bool)$this->r->set($key, $payload);
    }
    public function delete(string $key): bool
    {
        return (bool)$this->r->del($key);
    }
    public function clear(): bool
    {
        $this->r->flushDB();
        return true;
    }
    public function has(string $key): bool
    {
        return $this->r->exists($key) > 0;
    }
}
