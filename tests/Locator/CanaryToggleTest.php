<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\CanaryToggle;
final class CanaryToggleTest {
    public function testEnablePercent(): void {
        $c = new CanaryToggle();
        $c->enablePercent('f','t',50);
        $yes=0; for($i=0;$i<100;$i++){ if($c->isEnabled('f','t','u'+(string)$i)) $yes++; }
        assert($yes > 30 && $yes < 70);
        $c->disable('f','t');
        assert($c->isEnabled('f','t','u1')===false);
    }
}
