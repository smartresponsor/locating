<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface FailoverMatrixInterface {
    /** Configure fallback list for region/provider. */
    public function set(string $region, string $providerId, array $fallback): void;
    /** Return ordered fallback list including primary first. */
    public function chain(string $region, string $providerId): array;
}
