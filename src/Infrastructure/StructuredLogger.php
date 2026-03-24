<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Infrastructure;
final class StructuredLogger implements StructuredLoggerInterface {
    public function emit(string $name, array $field): string {
        $rec = ['ts'=>gmdate('c'),'name'=>$name];
        foreach ($field as $k=>$v){ $rec[(string)$k]=$v; }
        return json_encode($rec, JSON_UNESCAPED_SLASHES);
    }
}
