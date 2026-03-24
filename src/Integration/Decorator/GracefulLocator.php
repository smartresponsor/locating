<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Decorator;
use App\Contract\LocatorInterface;
use App\Model\AddressData;
use App\Model\GeoPoint;
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
