<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Infrastructure\Locator;
final class PriorityBatchQueue implements PriorityBatchQueueInterface {
    /** @var array<int, array<int, array{id:string,payload:array}>> priority => list */
    private array $q = [];
    public function enqueue(int $priority, array $payload): string {
        $p = max(-10, min(10, $priority));
        $id = bin2hex(random_bytes(6));
        $this->q[$p] = $this->q[$p] ?? [];
        $this->q[$p][] = ['id'=>$id,'payload'=>$payload];
        return $id;
    }
    public function dequeueBatch(int $max): array {
        $out = []; $need = max(1, $max);
        krsort($this->q, SORT_NUMERIC);
        foreach ($this->q as $p => $list) {
            while (!empty($this->q[$p]) && count($out) < $need) {
                $out[] = array_shift($this->q[$p]);
            }
            if (empty($this->q[$p])) { unset($this->q[$p]); }
            if (count($out) >= $need) { break; }
        }
        return $out;
    }
    public function length(): int {
        $n = 0; foreach ($this->q as $list) { $n += count($list); } return $n;
    }
}
