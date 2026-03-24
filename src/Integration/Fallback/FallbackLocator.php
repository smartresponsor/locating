<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Fallback;
use App\Contract\LocatorInterface;
use App\Model\AddressData;
use App\Model\GeoPoint;
final class FallbackLocator implements LocatorInterface{
  public function normalize(string $raw): AddressData{
    // dumb split
    $parts = array_map('trim', explode(',', $raw));
    $street = $parts[0] ?? ''; $city = $parts[1] ?? ''; $rest = $parts[2] ?? '';
    $region = ''; $postal = '';
    if ($rest !== ''){
      if (preg_match('~([A-Z]{2,})\s+(\S+)~', $rest, $m)){ $region=$m[1]; $postal=$m[2]; }
    }
    return new AddressData($street, $city, $region, $postal, '');
  }
  public function geocode(AddressData $a): GeoPoint{ return new GeoPoint(0.0,0.0); }
  public function reverse(GeoPoint $p): AddressData{ return new AddressData('','','','',''); }
}
