<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\PriorityBatchQueue;
final class PriorityBatchQueueTest {
    public function testEnqueueDequeue(): void {
        $q = new PriorityBatchQueue();
        $q->enqueue(1, ['a'=>1]);
        $q->enqueue(5, ['b'=>2]);
        $q->enqueue(0, ['c'=>3]);
        $b = $q->dequeueBatch(2);
        assert(count($b)===2);
        assert($q->length()===1);
    }
}
