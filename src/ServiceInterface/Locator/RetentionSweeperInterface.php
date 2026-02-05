<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface RetentionSweeperInterface {
    /**
     * Build deletion SQL for entities older than ttl; return list of SQL statements (strings).
     * Caller executes on Postgres/MySQL accordingly.
     */
    public function plan(array $entityToTable, RetentionPolicyInterface $policy): array;
}
