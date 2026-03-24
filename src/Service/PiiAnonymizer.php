<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class PiiAnonymizer {
    public function mask(array $row, array $map): array {
        foreach ($map as $field => $mode) {
            if (!array_key_exists($field, $row)) { continue; }
            $val = (string)$row[$field];
            if ($mode === 'hash') { $row[$field] = hash('sha256', $val); }
            elseif ($mode === 'null') { $row[$field] = null; }
            elseif ($mode === 'partial') { $row[$field] = substr($val, 0, 2) . str_repeat('*', max(0, strlen($val)-2)); }
        }
        return $row;
    }
}
