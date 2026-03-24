<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface RetentionSweeperInterface {
    /**
     * Build deletion SQL for entities older than ttl; return list of SQL statements (strings).
     * Caller executes on Postgres/MySQL accordingly.
     */
    public function plan(array $entityToTable, RetentionPolicyInterface $policy): array;
}
