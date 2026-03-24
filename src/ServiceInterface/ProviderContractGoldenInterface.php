<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface ProviderContractGoldenInterface {
    /** Record golden file under given path (provider/op). */
    public function record(string $providerId, string $op, array $request, array $response): void;
    /** Verify real response matches golden. If mismatch and $update=true, overwrite golden. */
    public function verify(string $providerId, string $op, array $request, array $response, bool $update=false): bool;
    /** Resolve golden file path for request. */
    public function path(string $providerId, string $op, array $request): string;
}
