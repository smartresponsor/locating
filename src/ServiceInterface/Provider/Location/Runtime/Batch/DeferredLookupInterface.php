<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Batch;

interface DeferredLookupInterface
{
    /** @param array<string,mixed> $payload */
    public function enqueue(array $payload): string;

    /** @return list<array{id:string,payload:array<string,mixed>}> */
    public function plan(int $limit): array;
    /** Mark job as done. */
    public function done(string $id): void;
}
