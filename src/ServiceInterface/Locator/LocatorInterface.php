<?php
declare(strict_types=1);
namespace Smartresponsor\ServiceInterface\Locator;
use Smartresponsor\Domain\Locator\AddressData; use Smartresponsor\Domain\Locator\GeoPoint;
interface LocatorInterface{ public function normalize(string $raw): AddressData; public function geocode(AddressData $a): GeoPoint; public function reverse(GeoPoint $p): AddressData; }
