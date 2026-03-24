<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Decorator;
use App\Contract\LocatorInterface;
use App\Model\AddressData;
use App\Model\GeoPoint;
final class CircuitBreakerLocator implements LocatorInterface{
  private int $fails=0; private int $openedAt=0;
  public function __construct(private LocatorInterface $inner, private int $threshold=5, private int $cooldown=30){}
  private function guard(): void{
    if($this->fails >= $this->threshold && (time()-$this->openedAt) < $this->cooldown){ throw new \RuntimeException('circuit-open'); }
    if($this->fails >= $this->threshold && (time()-$this->openedAt) >= $this->cooldown){ $this->fails = 0; } // move to half-open
  }
  private function wrap(callable $fn){
    $this->guard();
    try{ return $fn(); } catch(\Throwable $e){ $this->fails++; if($this->fails >= $this->threshold){ $this->openedAt=time(); } throw $e; }
  }
  public function normalize(string $raw): AddressData{ return $this->wrap(fn()=> $this->inner->normalize($raw)); }
  public function geocode(AddressData $a): GeoPoint{ return $this->wrap(fn()=> $this->inner->geocode($a)); }
  public function reverse(GeoPoint $p): AddressData{ return $this->wrap(fn()=> $this->inner->reverse($p)); }
}
