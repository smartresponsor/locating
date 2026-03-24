<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\SandboxHarness;
final class SandboxHarnessTest {
    public function testRecordReplay(): void {
        $h = new SandboxHarness();
        $h->record('p','sig1',['ok'=>true]);
        $r = $h->replay('p','sig1');
        assert($r !== null && $r['ok']===true);
        $h->clear('p','sig1');
        assert($h->replay('p','sig1')===null);
    }
}
