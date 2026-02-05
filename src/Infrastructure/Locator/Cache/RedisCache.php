<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace Smartresponsor\Infrastructure\Locator\Cache;

class RedisCache
{
    private ?\Redis $redis = null;
    public function __construct(string $url)
    {
        if (!$url) { return; }
        $parts = parse_url($url);
        if (!$parts || !isset($parts['host'])) { return; }
        $r = new \Redis();
        $ok = $r->connect($parts['host'], $parts['port'] ?? 6379, 1.0);
        if ($ok && isset($parts['pass'])) { $r->auth($parts['pass']); }
        if ($ok && isset($parts['path'])) { $db = (int)trim($parts['path'], '/'); $r->select($db); }
        $this->redis = $ok ? $r : null;
    }
    public function get(string $key): ?string
    {
        if (!$this->redis) return null;
        $v = $this->redis->get($key);
        return $v === false ? null : $v;
    }
    public function set(string $key, string $val, int $ttl): void
    {
        if (!$this->redis) return;
        $this->redis->setex($key, $ttl, $val);
    }
}
