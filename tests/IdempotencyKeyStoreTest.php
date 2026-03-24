<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\IdempotencyKeyStore;
final class IdempotencyKeyStoreTest {
    public function testAccept(): void {
        $s = new IdempotencyKeyStore();
        assert($s->accept('k', 60)===true);
        assert($s->accept('k', 60)===false);
    }
}
