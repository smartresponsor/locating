<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface RouterOrchestratorInterface {
    /** Orchestrate a single geocode lookup */
    public function route(array $request, TenantContextInterface $tenant): array;
}
