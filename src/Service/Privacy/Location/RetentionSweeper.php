<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Privacy\Location;

use App\Locating\PolicyInterface\Privacy\Location\RetentionPolicyInterface;
use App\Locating\ServiceInterface\Privacy\Location\RetentionSweeperInterface;

final class RetentionSweeper implements RetentionSweeperInterface
{
    /**
     * @param array<string,string> $entityToTable
     * @return list<string>
     */
    public function plan(array $entityToTable, RetentionPolicyInterface $policy): array
    {
        $sql = [];
        foreach ($entityToTable as $entity => $table) {
            $ttl = $policy->ttl($entity);
            $sql[] = 'DELETE FROM '.$table." WHERE created_at < NOW() - INTERVAL '".$ttl." day';";
        }
        return $sql;
    }
}
