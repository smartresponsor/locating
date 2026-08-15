<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Cache;

use Redis;

final class RedisFactory
{
    public static function createFromDsn(string $dsn): Redis
    {
        // DSN like redis://host:port/0
        $parts = parse_url($dsn);
        if (!$parts || ($parts['scheme'] ?? '') !== 'redis') {
            throw new \InvalidArgumentException('Invalid Redis DSN');
        }
        $host = $parts['host'] ?? '127.0.0.1';
        $port = (int)($parts['port'] ?? 6379);
        $db = isset($parts['path']) ? (int)trim($parts['path'], '/') : 0;
        $r = new Redis();
        $r->connect($host, $port, 2.0);
        if ($db) {
            $r->select($db);
        }
        return $r;
    }
}
