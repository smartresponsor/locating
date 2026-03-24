<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Decorator;
use App\Contract\LocatorInterface;
use App\Integration\Metrics\HealthMetrics;
use App\Model\AddressData;
use App\Model\GeoPoint;
final class HealthProbeLocator implements LocatorInterface{
  public function __construct(private LocatorInterface $inner, private HealthMetrics $m){}
  private function run(callable $fn){
    $t=microtime(true);
    try{ $res = $fn(); $this->m->record(microtime(true)-$t, true); return $res; }
    catch(\Throwable $e){ $this->m->record(microtime(true)-$t, false); throw $e; }
  }
  public function normalize(string $raw): AddressData{ return $this->run(fn()=> $this->inner->normalize($raw)); }
  public function geocode(AddressData $a): GeoPoint{ return $this->run(fn()=> $this->inner->geocode($a)); }
  public function reverse(GeoPoint $p): AddressData{ return $this->run(fn()=> $this->inner->reverse($p)); }
}
