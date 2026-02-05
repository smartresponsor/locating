<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\PriorityQueue;
use Smartresponsor\Layer\Locator\BatchScheduler;
final class PriorityQueueTest {
    public function testOrder(): void {
        $q = new PriorityQueue();
        $q->push('a', 1); $q->push('b', 5); $q->push('c', 3);
        assert($q->pop()==='b'); assert($q->pop()==='c'); assert($q->pop()==='a'); assert($q->pop()===null);
    }
    public function testScheduler(): void {
        $s = new BatchScheduler();
        $s->add(['q'=>'x'], 2, 500); $s->add(['q'=>'y'], 5, 800);
        $d = $s->drain(2);
        assert(count($d)===2 && $d[0]['deadline_ms']===800);
    }
}
