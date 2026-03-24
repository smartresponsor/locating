<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Http;
use App\Layer\RouterOrchestratorInterface;
use App\Layer\TenantContext;
final class RouteController {
    public function __construct(private RouterOrchestratorInterface $router){}
    public function get(array $query, string $tenantId): array {
        $tenant = new TenantContext($tenantId);
        return $this->router->route(['q'=>$query['q'] ?? '', 'region'=>$query['region'] ?? 'us'], $tenant);
    }
}
