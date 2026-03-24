<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\DeferredLookup;
final class DeferredLookupTest {
    public function testFlow(): void {
        $d = new DeferredLookup();
        $id = $d->enqueue(['text'=>'addr']);
        $batch = $d->plan(10);
        assert(count($batch)>=1 && $batch[0]['id']===$id);
        $d->done($id);
        $batch2 = $d->plan(10);
        assert(empty($batch2) || $batch2[0]['id']!==$id);
    }
}
