<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class DeferredLookup implements DeferredLookupInterface {
    /** @var array<string, array{payload:array, done:bool}> */
    private array $q = [];
    public function enqueue(array $payload): string {
        $id = bin2hex(random_bytes(6));
        $this->q[$id] = ['payload'=>$payload,'done'=>false];
        return $id;
    }
    public function plan(int $limit): array {
        $out = []; $n = max(1,$limit);
        foreach ($this->q as $id=>$row){
            if (!$row['done']) { $out[] = ['id'=>$id,'payload'=>$row['payload']]; }
            if (count($out) >= $n) { break; }
        }
        return $out;
    }
    public function done(string $id): void {
        if (isset($this->q[$id])){ $this->q[$id]['done']=true; }
    }
}
