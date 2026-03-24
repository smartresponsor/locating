<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Decorator;
use App\Contract\LocatorInterface;
use App\Model\AddressData;
use App\Model\GeoPoint;
final class RetryLocator implements LocatorInterface{
  public function __construct(private LocatorInterface $inner, private int $retries=2, private int $baseMs=100){}
  private function run(callable $fn){
    $e=null;
    for($i=0;$i<=$this->retries;$i++){
      try{ return $fn(); } catch(\Throwable $e){ usleep(($this->baseMs*(1<<$i)+rand(0,50))*1000); }
    }
    throw $e;
  }
  public function normalize(string $raw): AddressData{ return $this->run(fn()=> $this->inner->normalize($raw)); }
  public function geocode(AddressData $a): GeoPoint{ return $this->run(fn()=> $this->inner->geocode($a)); }
  public function reverse(GeoPoint $p): AddressData{ return $this->run(fn()=> $this->inner->reverse($p)); }
}
