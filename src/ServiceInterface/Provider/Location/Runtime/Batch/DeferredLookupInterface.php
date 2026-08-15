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
    /** Enqueue lookup request and return job id. */
    public function enqueue(array $payload): string;
    /** Plan batch for processing; returns list of payloads with ids. */
    public function plan(int $limit): array;
    /** Mark job as done. */
    public function done(string $id): void;
}
