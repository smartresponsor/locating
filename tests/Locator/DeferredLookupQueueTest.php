<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\DeferredLookupQueue;
final class DeferredLookupQueueTest {
    public function testEnqueueDequeue(): void {
        $q = new DeferredLookupQueue();
        $id = $q->enqueue('t',['q'=>'Main']);
        $job = $q->dequeue();
        assert($job !== null && $job['id'] === $id);
    }
}
