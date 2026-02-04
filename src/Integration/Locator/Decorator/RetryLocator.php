<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Decorator;
use SmartResponsor\Contract\Locator\LocatorInterface;
use SmartResponsor\Model\Locator\AddressData;
use SmartResponsor\Model\Locator\GeoPoint;
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
