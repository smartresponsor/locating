<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\Service\Location\Config\Env;
use App\Locating\Service\Provider\Location\Cache\RedisCache;
use App\Locating\Service\Provider\Location\Resilience\CircuitBreaker;
use App\Locating\Service\Provider\Location\Resilience\Hedger;
use App\Locating\Service\Provider\Location\Resilience\ProviderBudget;
use App\Locating\ServiceInterface\Provider\Location\GeocodeLocationProviderInterface;

class ProviderRouterService
{
    private RedisCache $cache;
    private MapboxProviderService $mapbox;
    private HereProviderService $here;
    private NominatimProviderService $nominatim;
    private CircuitBreaker $cbMapbox;
    private CircuitBreaker $cbHere;
    private ProviderBudget $budget;
    private int $hedgeDelay;

    public function __construct(Env $env, RedisCache $cache)
    {
        $this->cache = $cache;
        $this->mapbox = new MapboxProviderService($env);
        $this->here = new HereProviderService($env);
        $this->nominatim = new NominatimProviderService($env);
        $fail = (int) $env->get('CB_FAIL_THRESHOLD', '5');
        $ttl = (int) $env->get('CB_OPEN_TTL', '60');
        $this->cbMapbox = new CircuitBreaker($cache, 'mapbox', $fail, $ttl);
        $this->cbHere = new CircuitBreaker($cache, 'here', $fail, $ttl);
        $this->budget = new ProviderBudget($cache, (int) $env->get('PROVIDER_BUDGET_PER_MIN', '600'));
        $this->hedgeDelay = (int) $env->get('HEDGE_DELAY_MS', '120');
    }

    /** @return list<array<string, mixed>> */
    public function geocode(string $q, string $country): array
    {
        $key = 'geo:'.md5($q.'|'.$country);
        if ($hit = $this->cache->get($key)) {
            $cached = $this->normalizeResults(json_decode($hit, true));
            if ([] !== $cached) {
                return $cached;
            }
        }
        $res = Hedger::race([
            function () use ($q, $country): array {
                return $this->tryProvider($this->mapbox, 'mapbox', 'geocode', [$q, $country]);
            },
            function () use ($q, $country): array {
                return $this->tryProvider($this->here, 'here', 'geocode', [$q, $country]);
            },
        ], $this->hedgeDelay);
        $res = $this->normalizeResults($res);
        if (!$res) {
            $res = $this->tryProvider($this->nominatim, 'nominatim', 'geocode', [$q, $country]);
        }
        $res = ProviderRankerService::sort($res);
        if ($res) {
            $this->cache->set($key, json_encode($res, JSON_THROW_ON_ERROR), 30);
        }

        return $res;
    }

    /** @return list<array<string, mixed>> */
    public function reverse(float $lat, float $lon): array
    {
        $key = 'rev:'.md5((string) $lat.'|'.(string) $lon);
        if ($hit = $this->cache->get($key)) {
            $cached = $this->normalizeResults(json_decode($hit, true));
            if ([] !== $cached) {
                return $cached;
            }
        }
        $res = Hedger::race([
            function () use ($lat, $lon): array {
                return $this->tryProvider($this->mapbox, 'mapbox', 'reverse', [$lat, $lon]);
            },
            function () use ($lat, $lon): array {
                return $this->tryProvider($this->here, 'here', 'reverse', [$lat, $lon]);
            },
        ], $this->hedgeDelay);
        $res = $this->normalizeResults($res);
        if (!$res) {
            $res = $this->tryProvider($this->nominatim, 'nominatim', 'reverse', [$lat, $lon]);
        }
        if ($res) {
            $this->cache->set($key, json_encode($res, JSON_THROW_ON_ERROR), 30);
        }

        return $res;
    }

    /** @return list<array<string, mixed>> */
    public function autocomplete(string $q, string $country, string $bbox): array
    {
        $key = 'ac:'.md5($q.'|'.$country.'|'.$bbox);
        if ($hit = $this->cache->get($key)) {
            $cached = $this->normalizeResults(json_decode($hit, true));
            if ([] !== $cached) {
                return $cached;
            }
        }
        $res = Hedger::race([
            function () use ($q, $country, $bbox): array {
                return $this->tryProvider($this->mapbox, 'mapbox', 'autocomplete', [$q, $country, $bbox]);
            },
            function () use ($q, $country, $bbox): array {
                return $this->tryProvider($this->here, 'here', 'autocomplete', [$q, $country, $bbox]);
            },
        ], $this->hedgeDelay);
        $res = $this->normalizeResults($res);
        if (!$res) {
            $res = $this->tryProvider($this->nominatim, 'nominatim', 'autocomplete', [$q, $country, $bbox]);
        }
        if ($res) {
            $this->cache->set($key, json_encode($res, JSON_THROW_ON_ERROR), 10);
        }

        return $res;
    }

    /**
     * @param 'geocode'|'reverse'|'autocomplete' $method
     * @param list<mixed> $args
     * @return list<array<string, mixed>>
     */
    private function tryProvider(GeocodeLocationProviderInterface $provider, string $nameEntity, string $method, array $args): array
    {
        $cb = 'mapbox' === $nameEntity ? $this->cbMapbox : $this->cbHere;
        if ('nominatim' !== $nameEntity) {
            if (!$cb->allow()) {
                return [];
            }
            if (!$this->budget->allow($nameEntity)) {
                return [];
            }
        }
        try {
            $res = call_user_func_array([$provider, $method], $args);
            if ('nominatim' !== $nameEntity) {
                $cb->recordSuccess();
            }

            return $this->normalizeResults($res);
        } catch (\Throwable $e) {
            if ('nominatim' !== $nameEntity) {
                $cb->recordFailure();
            }

            return [];
        }
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function normalizeResults(mixed $value): array
    {
        if (!is_array($value)) {
            return [];
        }

        $results = [];
        foreach ($value as $item) {
            if (!is_array($item)) {
                continue;
            }
            $normalized = [];
            foreach ($item as $key => $entry) {
                if (is_string($key)) {
                    $normalized[$key] = $entry;
                }
            }
            $results[] = $normalized;
        }

        return $results;
    }
}
