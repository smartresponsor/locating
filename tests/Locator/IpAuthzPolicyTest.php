<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\IpAuthzPolicy;
final class IpAuthzPolicyTest {
    public function testAllowDeny(): void {
        $p = new IpAuthzPolicy();
        $p->add('t','deny','10.0.0.0/8', 10);
        $p->add('t','allow','10.20.0.0/16', 5);
        assert($p->allow('t','1.2.3.4')===true);
        assert($p->allow('t','10.20.1.2')===true);
        assert($p->allow('t','10.1.2.3')===false);
    }
}
