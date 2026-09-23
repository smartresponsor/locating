<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Queue;

use App\Locating\ServiceInterface\Provider\Location\Queue\PriorityBatchQueueInterface;

final class PriorityBatchQueue implements PriorityBatchQueueInterface
{
    /** @var array<int,list<array{id:string,payload:array<string,mixed>}>> */
    private array $q = [];

    /** @param array<string,mixed> $payload */
    public function enqueue(int $priority, array $payload): string
    {
        $p = max(-10, min(10, $priority));
        $id = bin2hex(random_bytes(6));
        $this->q[$p] = $this->q[$p] ?? [];
        $this->q[$p][] = ['id' => $id, 'payload' => $payload];

        return $id;
    }

    /** @return list<array{id:string,payload:array<string,mixed>}> */
    public function dequeueBatch(int $max): array
    {
        $out = [];
        $need = max(1, $max);
        krsort($this->q, SORT_NUMERIC);
        foreach ($this->q as $p => $list) {
            while (!empty($this->q[$p]) && count($out) < $need) {
                $out[] = array_shift($this->q[$p]);
            }
            if (empty($this->q[$p])) {
                unset($this->q[$p]);
            }
            if (count($out) >= $need) {
                break;
            }
        }

        return $out;
    }

    public function length(): int
    {
        $n = 0;
        foreach ($this->q as $list) {
            $n += count($list);
        }

        return $n;
    }
}
