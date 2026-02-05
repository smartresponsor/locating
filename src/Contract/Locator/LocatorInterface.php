<?php
declare(strict_types=1);
namespace Smartresponsor\Contract\Locator;
use Smartresponsor\Model\Locator\AddressData;
use Smartresponsor\Model\Locator\GeoPoint;
interface LocatorInterface{
  public function normalize(string $raw): AddressData;
  public function geocode(AddressData $a): GeoPoint;
  public function reverse(GeoPoint $p): AddressData;
}
