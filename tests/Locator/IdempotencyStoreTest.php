<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use Smartresponsor\Layer\Locator\IdempotencyStore;
final class IdempotencyStoreTest {
    public function testCheckAndPut(): void {
        $s = new IdempotencyStore();
        assert($s->checkAndPut('k', 60) === true);
        assert($s->checkAndPut('k', 60) === false);
    }
}
