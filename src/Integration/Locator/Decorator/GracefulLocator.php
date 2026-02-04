<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Decorator;
use SmartResponsor\Contract\Locator\LocatorInterface;
use SmartResponsor\Model\Locator\AddressData;
use SmartResponsor\Model\Locator\GeoPoint;
final class GracefulLocator implements LocatorInterface{
  public function __construct(private LocatorInterface $primary, private LocatorInterface $fallback){}
  public function normalize(string $raw): AddressData{
    try{ return $this->primary->normalize($raw); }catch(\Throwable){ return $this->fallback->normalize($raw); }
  }
  public function geocode(AddressData $a): GeoPoint{
    try{ return $this->primary->geocode($a); }catch(\Throwable){ return $this->fallback->geocode($a); }
  }
  public function reverse(GeoPoint $p): AddressData{
    try{ return $this->primary->reverse($p); }catch(\Throwable){ return $this->fallback->reverse($p); }
  }
}
