<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class FakeRedis implements RedisClientInterface {
    /** @var array<string, array{val:int, exp:int}> */
    private array $store = [];
    public function incrby(string $key, int $n): int {
        $now = time();
        $row = $this->store[$key] ?? ['val'=>0,'exp'=>0];
        if ($row['exp'] > 0 && $now >= $row['exp']) { $row = ['val'=>0,'exp'=>0]; }
        $row['val'] += $n;
        $this->store[$key] = $row;
        return $row['val'];
    }
    public function get(string $key): ?string {
        $now=time(); $row=$this->store[$key]??null;
        if($row===null){return null;}
        if($row['exp']>0 && $now>=$row['exp']){unset($this->store[$key]); return null;}
        return (string)$row['val'];
    }
    public function expire(string $key, int $ttl): void {
        $now = time();
        $row = $this->store[$key] ?? ['val'=>0,'exp'=>0];
        $row['exp'] = $now + max(1,$ttl);
        $this->store[$key] = $row;
    }
    public function pttl(string $key): int {
        $now=time(); $row=$this->store[$key]??['exp'=>0];
        return ($row['exp']>0) ? max(0, ($row['exp']-$now)*1000) : -1;
    }
    public function del(string $key): void { unset($this->store[$key]); }
}
