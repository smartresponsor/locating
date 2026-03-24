<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Service;
use Smartresponsor\Contract\ProviderInterface;
final class ForwardAggregator{
  public function __construct(private array $providers, private HealthMonitor $monitor, private AdaptiveRouter $adaptive, private CircuitBreaker $cb, private RegionRouter $region){}
  public function locate(string $q, ?string $region=null): array{
    $providers=$this->region->filterByRegion($this->providers, $region);
    $ordered=$this->adaptive->order($providers);
    foreach($ordered as $p){
      if(!$this->cb->allow($p->name())){ continue; }
      $t0=microtime(true);
      try{
        $res=$p->geocode($q); $ms=(microtime(true)-$t0)*1000.0; $this->monitor->update($p->name(), true, $ms); $this->cb->onSuccess($p->name());
        return ['lat'=>$res['lat'],'lon'=>$res['lon'],'provider'=>$p->name(),'formatted'=>$res['formatted']];
      }catch(\Throwable $e){
        $ms=(microtime(true)-$t0)*1000.0; $this->monitor->update($p->name(), false, $ms); $this->cb->onFailure($p->name());
      }
    }
    $h=substr(sha1(trim(strtolower($q))),0,8); $lat=(int)hexdec(substr($h,0,4))%90; $lon=(int)hexdec(substr($h,4,4))%180;
    return ['lat'=>$lat+0.1234,'lon'=>$lon+0.5678,'provider'=>'fallback','formatted'=>ucwords($q)];
  }
}
