<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\RecordReplayStore;
final class RecordReplayStoreTest {
    public function testRecordReplay(): void {
        $s = new RecordReplayStore();
        $k = 'p:geo:abcd';
        $s->record($k, ['text'=>'addr'], ['lat'=>1,'lon'=>2]);
        $x = $s->replay($k);
        assert($x !== null && $x['lat']===1 && $x['lon']===2);
    }
}
