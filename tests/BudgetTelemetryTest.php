<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\BudgetTelemetry;
final class BudgetTelemetryTest {
    public function testExport(): void {
        $t = new BudgetTelemetry();
        $out = $t->export([['tenantId'=>'t','op'=>'geocode','remaining'=>10,'used'=>5]]);
        assert(strpos($out,'locator_budget_remaining')!==false && strpos($out,'tenant="t"')!==false);
    }
}
