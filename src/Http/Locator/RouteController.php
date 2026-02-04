<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Http\Locator;
use App\Layer\Locator\RouterOrchestratorInterface;
use App\Layer\Locator\TenantContext;
final class RouteController {
    public function __construct(private RouterOrchestratorInterface $router){}
    public function get(array $query, string $tenantId): array {
        $tenant = new TenantContext($tenantId);
        return $this->router->route(['q'=>$query['q'] ?? '', 'region'=>$query['region'] ?? 'us'], $tenant);
    }
}
