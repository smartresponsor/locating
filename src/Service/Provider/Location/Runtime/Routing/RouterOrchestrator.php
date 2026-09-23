<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Routing;

use App\Locating\Service\Provider\Location\Cache\ResultCache;
use App\Locating\Service\Provider\Location\HealthEwmaService;
use App\Locating\Service\Provider\Location\Runtime\Resilience\FailoverMatrix;
use App\Locating\Service\Provider\Location\Runtime\Resilience\RetryPolicy;
use App\Locating\Service\Provider\Location\Runtime\Resilience\SlaPolicy;
use App\Locating\ServiceInterface\Location\RateLimit\RateLimiterInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantContextInterface;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Routing\RouterOrchestratorInterface;

final class RouterOrchestrator implements RouterOrchestratorInterface
{
    public function __construct(
        private RateLimiterInterface $limiter,
        private QuotaGuard $quota,
        private ResultCache $cache,
        private HealthEwmaService $health,
        private SlaPolicy $sla,
        private RegionRouter $region,
        private FailoverMatrix $failover,
        private RetryPolicy $retry
    ) {
    }
    /**
     * @param array<string, mixed> $request
     * @return array<string, mixed>
     */
    public function route(array $request, TenantContextInterface $tenant): array
    {
        $key = 'locator:' . $tenant->id();
        if (!$this->limiter->allow($key, 1, 20, 40)) {
            return ['status' => 'error','error' => 'rate_limited','tenant' => $tenant->id()];
        }
        $q = is_string($request['q'] ?? null) ? $request['q'] : '';
        $requestRegion = is_string($request['region'] ?? null) ? $request['region'] : 'us';
        $norm = ['q' => $q, 'region' => $requestRegion];
        $ck = 'route:'.hash('sha256', json_encode($norm, JSON_THROW_ON_ERROR));
        if (($hit = $this->cache->get($ck)) !== null) {
            return ['status' => 'ok','cached' => true] + $hit;
        }
        if (!$this->quota->charge($tenant->id(), 0.01)) {
            return ['status' => 'error','error' => 'quota_exceeded','tenant' => $tenant->id()];
        }
        $region = $norm['region'];
        $slaWeight = [$region => $this->sla->weight(200.0, 200.0, 0.02, 0.02)];
        $selectedRegion = $this->region->select([$region => 0.9], $slaWeight);
        $candidates = $this->failover->candidate($selectedRegion, 'prov-a');
        $attempt = 0;
        foreach ($candidates as $prov) {
            $attempt++;
            $lat = 100.0 + $attempt * 10.0; // mock latency
            $err = 0.01 * $attempt;
            $score = $this->health->health($lat, $err);
            if ($score < 0.2) {
                continue;
            }
            $res = ['provider' => $prov,'region' => $selectedRegion,'latency_ms' => $lat,'score' => $score];
            $this->cache->put($ck, $res, 120);
            return ['status' => 'ok','cached' => false] + $res;
        }
        // Retry loop (simplified)
        for ($i = 0;$i < 2;$i++) {
            if ($this->retry->shouldRetry($i, 2, 503)) { /* jitter sleep omitted */ continue;
            }
        }
        return ['status' => 'error','error' => 'no_route'];
    }
}
