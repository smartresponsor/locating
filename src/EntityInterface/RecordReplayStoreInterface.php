<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface RecordReplayStoreInterface {
    /** Record response by key (provider/op/input-hash). */
    public function record(string $key, array $request, array $response): void;
    /** Return replayed response by key, or null. */
    public function replay(string $key): ?array;
}
