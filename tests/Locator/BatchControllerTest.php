<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Http\Locator\BatchController;
use Smartresponsor\Layer\Locator\BatchService;
final class BatchControllerTest {
    public function testPost(): void {
        $c = new BatchController(new BatchService());
        $r = $c->post(['items'=>['a','b','c'],'deadline_ms'=>800]);
        assert($r['count'] === 3 && $r['deadline_ms'] === 800);
    }
}
