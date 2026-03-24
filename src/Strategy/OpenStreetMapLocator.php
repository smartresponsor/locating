<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Strategy;
use Smartresponsor\Contract\LocatorInterface;
use Smartresponsor\Integration\Http\NominatimClient;
use Smartresponsor\Model\AddressData;
use Smartresponsor\Model\GeoPoint;
final class OpenStreetMapLocator implements LocatorInterface{
  public function __construct(private NominatimClient $client){}
  public function normalize(string $raw): AddressData{
    $list = $this->client->search($raw, 1);
    if ($list === [] || !isset($list[0]['address'])) {
      return new AddressData('','','','','');
    }
    $a = $list[0]['address'];
    return new AddressData(
      trim(($a['house_number'] ?? '') . ' ' . ($a['road'] ?? '')),
      (string)($a['city'] ?? $a['town'] ?? $a['village'] ?? ''),
      (string)($a['state'] ?? ''),
      (string)($a['postcode'] ?? ''),
      strtoupper((string)($a['country_code'] ?? ''))
    );
  }
  public function geocode(AddressData $a): GeoPoint{
    $q = trim($a->street . ', ' . $a->city . ', ' . $a->region . ' ' . $a->postalCode . ', ' . $a->countryCode);
    $list = $this->client->search($q, 1);
    if ($list === [] || !isset($list[0]['lat'], $list[0]['lon'])) {
      return new GeoPoint(0.0, 0.0);
    }
    return new GeoPoint((float)$list[0]['lat'], (float)$list[0]['lon']);
  }
  public function reverse(GeoPoint $p): AddressData{
    $obj = $this->client->reverse($p->latitude, $p->longitude);
    $a = $obj['address'] ?? [];
    return new AddressData(
      trim(($a['house_number'] ?? '') . ' ' . ($a['road'] ?? '')),
      (string)($a['city'] ?? $a['town'] ?? $a['village'] ?? ''),
      (string)($a['state'] ?? ''),
      (string)($a['postcode'] ?? ''),
      strtoupper((string)($a['country_code'] ?? ''))
    );
  }
}
