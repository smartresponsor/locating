<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace SmartResponsor\Service\Locator\Provider;
use SmartResponsor\Domain\Locator\Config\Env;
use SmartResponsor\Infrastructure\Locator\Cache\RedisCache;
use SmartResponsor\Infrastructure\Locator\Resilience\CircuitBreaker;
use SmartResponsor\Infrastructure\Locator\Resilience\ProviderBudget;
use SmartResponsor\Infrastructure\Locator\Resilience\Hedger;

class ProviderRouter {
    private Env $env; private RedisCache $cache;
    private MapboxProvider $mapbox; private HereProvider $here; private NominatimProvider $nominatim;
    private CircuitBreaker $cbMapbox; private CircuitBreaker $cbHere; private ProviderBudget $budget;
    private int $hedgeDelay;

    public function __construct(Env $env, RedisCache $cache){
        $this->env=$env; $this->cache=$cache;
        $this->mapbox=new MapboxProvider($env); $this->here=new HereProvider($env); $this->nominatim=new NominatimProvider($env);
        $fail=(int)$env->get('CB_FAIL_THRESHOLD','5'); $ttl=(int)$env->get('CB_OPEN_TTL','60');
        $this->cbMapbox=new CircuitBreaker($cache,'mapbox',$fail,$ttl);
        $this->cbHere=new CircuitBreaker($cache,'here',$fail,$ttl);
        $this->budget=new ProviderBudget($cache,(int)$env->get('PROVIDER_BUDGET_PER_MIN','600'));
        $this->hedgeDelay=(int)$env->get('HEDGE_DELAY_MS','120');
    }

    public function geocode(string $q,string $country): array{
        $key='geo:'.md5($q.'|'.$country); if($hit=$this->cache->get($key)) return json_decode($hit,true);
        $res = Hedger::race([ function(){ return $this->tryProvider($this->mapbox,'mapbox','geocode',[$q,$country]); },
                               function(){ return $this->tryProvider($this->here,'here','geocode',[$q,$country]); } ], $this->hedgeDelay);
        if(!$res){ $res = $this->tryProvider($this->nominatim,'nominatim','geocode',[$q,$country]); }
        $res = Ranker::sort($res);
        if($res) $this->cache->set($key,json_encode($res),30);
        return $res;
    }

    public function reverse(float $lat,float $lon): array{
        $key='rev:'.md5((string)$lat.'|'.(string)$lon); if($hit=$this->cache->get($key)) return json_decode($hit,true);
        $res = Hedger::race([ function(){ return $this->tryProvider($this->mapbox,'mapbox','reverse',[$lat,$lon]); },
                               function(){ return $this->tryProvider($this->here,'here','reverse',[$lat,$lon]); } ], $this->hedgeDelay);
        if(!$res){ $res = $this->tryProvider($this->nominatim,'nominatim','reverse',[$lat,$lon]); }
        if($res) $this->cache->set($key,json_encode($res),30);
        return $res;
    }

    public function autocomplete(string $q,string $country,string $bbox): array{
        $key='ac:'.md5($q.'|'.$country.'|'.$bbox); if($hit=$this->cache->get($key)) return json_decode($hit,true);
        $res = Hedger::race([ function(){ return $this->tryProvider($this->mapbox,'mapbox','autocomplete',[$q,$country,$bbox]); },
                               function(){ return $this->tryProvider($this->here,'here','autocomplete',[$q,$country,$bbox]); } ], $this->hedgeDelay);
        if(!$res){ $res = $this->tryProvider($this->nominatim,'nominatim','autocomplete',[$q,$country,$bbox]); }
        if($res) $this->cache->set($key,json_encode($res),10);
        return $res;
    }

    private function tryProvider($prov, string $name, string $method, array $args): array {
        $cb = $name==='mapbox'?$this->cbMapbox:$this->cbHere;
        if($name!=='nominatim'){
            if(!$cb->allow()) return [];
            if(!$this->budget->allow($name)) return [];
        }
        try {
            $res = call_user_func_array([$prov,$method], $args);
            if($name!=='nominatim'){ $cb->recordSuccess(); }
            return $res;
        } catch(\Throwable $e){
            if($name!=='nominatim'){ $cb->recordFailure(); }
            return [];
        }
    }
}
