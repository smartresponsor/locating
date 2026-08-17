<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Privacy\Location;

final class PiiAnonymizer
{
    /**
     * @param array<string,mixed> $row
     * @param array<string,'hash'|'null'|'partial'> $map
     * @return array<string,mixed>
     */
    public function mask(array $row, array $map): array
    {
        foreach ($map as $field => $mode) {
            if (!array_key_exists($field, $row)) {
                continue;
            }
            $raw = $row[$field];
            $val = is_scalar($raw) || $raw instanceof \Stringable ? (string) $raw : '';
            if ($mode === 'hash') {
                $row[$field] = hash('sha256', $val);
            } elseif ($mode === 'null') {
                $row[$field] = null;
            } elseif ($mode === 'partial') {
                $row[$field] = substr($val, 0, 2) . str_repeat('*', max(0, strlen($val) - 2));
            }
        }
        return $row;
    }
}
