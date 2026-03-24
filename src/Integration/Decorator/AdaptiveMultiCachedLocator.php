<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Decorator;
use App\Contract\LocatorInterface;
use App\Integration\Cache\CacheInterface;
use App\Integration\Cache\AdaptiveTtlPolicy;
use App\Integration\Metrics\HealthMetrics;
use App\Model\AddressData;
use App\Model\GeoPoint;
final class AdaptiveMultiCachedLocator implements LocatorInterface{
  private const NEG = '__NEG__';
  public function __construct(
    private LocatorInterface $inner,
    private CacheInterface $cache,
    private AdaptiveTtlPolicy $ttl,
    private HealthMetrics $metrics,
    private int $negTtl=60
  ){}
  private function k(string $p, array $a): string{ return $p.':'.md5(json_encode($a)); }
  private function decideTtl(): int{
    $s = $this->metrics->snapshot();
    return $this->ttl->decide($s['avg_ms'], $s['p95_ms'], (float)$s['error_rate']);
  }
  public function normalize(string $raw): AddressData{
    $k=$this->k('norm',[$raw]); $v=$this->cache->get($k);
    if($v===self::NEG){ return new AddressData('','','','',''); }
    if(is_array($v)){ return AddressData::fromArray($v); }
    $r=$this->inner->normalize($raw);
    $arr=$r->toArray(); $ttl=$this->decideTtl();
    if($arr['street']==='' && $arr['city']==='' && $arr['postalCode']===''){ $this->cache->set($k,self::NEG,$this->negTtl); }
    else{ $this->cache->set($k,$arr,$ttl); }
    return $r;
  }
  public function geocode(AddressData $a): GeoPoint{
    $ka=$this->k('geo',[$a->toArray()]); $v=$this->cache->get($ka);
    if($v===self::NEG){ return new GeoPoint(0.0,0.0); }
    if(is_array($v) && isset($v['lat'])){ return new GeoPoint($v['lat'],$v['lon']); }
    $r=$this->inner->geocode($a);
    $ttl=$this->decideTtl();
    if($r->latitude==0.0 and $r->longitude==0.0){ $this->cache->set($ka,self::NEG,$this->negTtl); }
    else{
      $this->cache->set($ka,$r->toArray(),$ttl);
      $kr=$this->k('rev',[$r->latitude,$r->longitude]); $this->cache->set($kr,$a->toArray(),$ttl);
    }
    return $r;
  }
  public function reverse(GeoPoint $p): AddressData{
    $kr=$this->k('rev',[$p->latitude,$p->longitude]); $v=$this->cache->get($kr);
    if($v===self::NEG){ return new AddressData('','','','',''); }
    if(is_array($v)){ return AddressData::fromArray($v); }
    $r=$this->inner->reverse($p);
    $arr=$r->toArray(); $ttl=$this->decideTtl();
    if($arr['street']==='' && $arr['city']===''){ $this->cache->set($kr,self::NEG,$this->negTtl); }
    else{
      $this->cache->set($kr,$arr,$ttl);
      $kg=$this->k('geo',[$arr]); $this->cache->set($kg,['lat'=>$p->latitude,'lon'=>$p->longitude],$ttl);
    }
    return $r;
  }
}
