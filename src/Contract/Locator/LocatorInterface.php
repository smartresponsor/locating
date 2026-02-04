<?php
declare(strict_types=1);
namespace SmartResponsor\Contract\Locator;
use SmartResponsor\Model\Locator\AddressData;
use SmartResponsor\Model\Locator\GeoPoint;
interface LocatorInterface{
  public function normalize(string $raw): AddressData;
  public function geocode(AddressData $a): GeoPoint;
  public function reverse(GeoPoint $p): AddressData;
}
