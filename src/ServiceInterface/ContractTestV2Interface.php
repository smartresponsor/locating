<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface ContractTestV2Interface {
    /** Validate response structure for provider/op/version, return list of issue strings (empty if ok). */
    public function validate(string $providerId, string $op, string $version, array $response): array;
}
