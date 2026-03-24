<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests;
use Smartresponsor\Layer\IdempotencyStore;
final class IdempotencyStoreTest {
    public function testCheckAndPut(): void {
        $s = new IdempotencyStore();
        assert($s->checkAndPut('k', 60) === true);
        assert($s->checkAndPut('k', 60) === false);
    }
}
