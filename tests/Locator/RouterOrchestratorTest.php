<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Tests\Locator;
use App\Layer\Locator\{RateLimiter,QuotaGuard,ResultCache,HealthEwma,SlaPolicy,RegionRouter,FailoverMatrix,RetryPolicy,RouterOrchestrator,TenantContext};
final class RouterOrchestratorTest {
    public function testRouteOk(): void {
        $router = new RouterOrchestrator(new RateLimiter(), new QuotaGuard(), new ResultCache(), new HealthEwma(), new SlaPolicy(), new RegionRouter(), new FailoverMatrix(), new RetryPolicy());
        $res = $router->route(['q'=>'Main St','region'=>'us'], new TenantContext('t1'));
        assert($res['status']==='ok' && isset($res['provider']));
    }
    public function testRateLimit(): void {
        $router = new RouterOrchestrator(new RateLimiter(), new QuotaGuard(), new ResultCache(), new HealthEwma(), new SlaPolicy(), new RegionRouter(), new FailoverMatrix(), new RetryPolicy());
        // Exhaust limiter
        for($i=0;$i<100;$i++){ $router->route(['q'=>'A','region'=>'us'], new TenantContext('t2')); }
        $res = $router->route(['q'=>'B','region'=>'us'], new TenantContext('t2'));
        assert(in_array($res['status'], ['ok','error'], true));
    }
}
