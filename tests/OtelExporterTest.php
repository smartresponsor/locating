<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use App\Layer\OtelExporter;
final class OtelExporterTest {
    public function testExport(): void {
        $e = new OtelExporter();
        $json = $e->export(['name'=>'test.span','attr'=>['k'=>'v']]);
        $obj = json_decode($json, true);
        assert($obj['name']==='test.span' && isset($obj['traceId']) && isset($obj['spanId']));
    }
}
