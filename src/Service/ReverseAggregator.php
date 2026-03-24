<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Service;
use App\Contract\ReverseProviderInterface;
final class ReverseAggregator{
  public function __construct(private array $providers, private Normalizer $normalizer, private HealthMonitor $monitor, private AdaptiveRouter $adaptive, private CircuitBreaker $cb, private RegionRouter $region){}
  public function reverse(float $lat, float $lon, ?string $region=null): array{
    $providers=$this->region->filterByRegion($this->providers, $region);
    $ordered=$this->adaptive->order($providers);
    foreach($ordered as $p){
      if(!$this->cb->allow($p->name())){ continue; }
      $t0=microtime(true);
      try{
        $raw=$p->reverse($lat,$lon); $ms=(microtime(true)-$t0)*1000.0; $this->monitor->update($p->name(), true, $ms); $this->cb->onSuccess($p->name());
        $norm=$this->normalizer->canonicalize($raw, $p->name());
        return ['address'=>$norm['address'],'score'=>$norm['score'],'provider'=>$p->name()];
      }catch(\Throwable $e){
        $ms=(microtime(true)-$t0)*1000.0; $this->monitor->update($p->name(), false, $ms); $this->cb->onFailure($p->name());
      }
    }
    return ['address'=>(object)['street'=>'','house'=>'','city'=>'','region'=>'','postalCode'=>'','countryCode'=>'','formatted'=>''],'score'=>0.1,'provider'=>'fallback'];
  }
}
