<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Batch;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Batch\DeferredLookupInterface;

final class DeferredLookup implements DeferredLookupInterface
{
    /** @var array<string,array{payload:array<string,mixed>,done:bool}> */
    private array $q = [];

    /** @param array<string,mixed> $payload */
    public function enqueue(array $payload): string
    {
        $id = bin2hex(random_bytes(6));
        $this->q[$id] = ['payload' => $payload,'done' => false];
        return $id;
    }
    /** @return list<array{id:string,payload:array<string,mixed>}> */
    public function plan(int $limit): array
    {
        $out = [];
        $n = max(1, $limit);
        foreach ($this->q as $id => $row) {
            if (!$row['done']) {
                $out[] = ['id' => $id,'payload' => $row['payload']];
            }
            if (count($out) >= $n) {
                break;
            }
        }
        return $out;
    }
    public function done(string $id): void
    {
        if (isset($this->q[$id])) {
            $this->q[$id]['done'] = true;
        }
    }
}
