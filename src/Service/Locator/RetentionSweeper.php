<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class RetentionSweeper implements RetentionSweeperInterface {
    public function plan(array $entityToTable, RetentionPolicyInterface $policy): array {
        $sql = [];
        foreach ($entityToTable as $entity => $tbl) {
            $ttl = $policy->ttl((string)$entity);
            $sql[] = "DELETE FROM ".$tbl." WHERE created_at < NOW() - INTERVAL '" . (int)$ttl . " day';";
        }
        return $sql;
    }
}
