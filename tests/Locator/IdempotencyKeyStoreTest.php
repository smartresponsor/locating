<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\IdempotencyKeyStore;
final class IdempotencyKeyStoreTest {
    public function testAccept(): void {
        $s = new IdempotencyKeyStore();
        assert($s->accept('k', 60)===true);
        assert($s->accept('k', 60)===false);
    }
}
