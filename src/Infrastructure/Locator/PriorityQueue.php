<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Infrastructure\Locator;
final class PriorityQueue {
    /** @var array<int, array{p:int, v:mixed}> */
    private array $buf = [];
    public function push(mixed $value, int $priority=0): void {
        $this->buf[] = ['p'=>$priority, 'v'=>$value];
    }
    public function pop(): mixed {
        if (empty($this->buf)) { return null; }
        usort($this->buf, fn($a,$b)=>$b['p']<=>$a['p']);
        $row = array_shift($this->buf);
        return $row['v'];
    }
    public function len(): int { return count($this->buf); }
}
