<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
/**
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace App\Infrastructure\Metrics;
use App\Infrastructure\Cache\RedisCache;

class Prometheus
{
    private RedisCache $cache;
    public function __construct(RedisCache $cache){ $this->cache=$cache; }

    public function inc(string $name, float $v=1.0): void {
        $key = 'pm:' . $name;
        $raw = $this->cache->get($key);
        $val = $raw ? (float)$raw : 0.0;
        $this->cache->set($key, (string)($val + $v), 3600);
    }

    public function render(array $series): string {
        $lines = [];
        foreach ($series as $name => $help) {
            $lines[] = "# HELP {$name} {$help}";
            $lines[] = "# TYPE {$name} counter";
            $val = $this->cache->get('pm:'.$name);
            $lines.append("{$name} " . ($val ? $val : '0'));
        }
        return implode("\n", $lines) . "\n";
    }
}
