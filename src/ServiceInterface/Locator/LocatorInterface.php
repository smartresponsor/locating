<?php
declare(strict_types=1);
namespace App\ServiceInterface\Locator;
use App\Domain\Locator\AddressData; use App\Domain\Locator\GeoPoint;
interface LocatorInterface{ public function normalize(string $raw): AddressData; public function geocode(AddressData $a): GeoPoint; public function reverse(GeoPoint $p): AddressData; }
