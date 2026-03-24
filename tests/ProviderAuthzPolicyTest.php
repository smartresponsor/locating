<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\ProviderAuthzPolicy;
final class ProviderAuthzPolicyTest {
    public function testAllowDeny(): void {
        $p = new ProviderAuthzPolicy();
        $p->add('t','*','*','*','allow', 100);
        $p->add('t','us','geocode','p1','deny', 10);
        assert($p->allow('t','us','geocode','p1')===false);
        assert($p->allow('t','us','geocode','p2')===true);
    }
}
