<?php
declare(strict_types=1);
namespace Smartresponsor\Strategy\Locator;
use Smartresponsor\Contract\Locator\LocatorInterface;
use Smartresponsor\Integration\Locator\Http\GoogleGeocodingClient;
use Smartresponsor\Model\Locator\AddressData;
use Smartresponsor\Model\Locator\GeoPoint;
final class GoogleLocator implements LocatorInterface{
  public function __construct(private GoogleGeocodingClient $client){}
  private static function mapAddressComponents(array $components): array{
    $map = [];
    foreach ($components as $comp) {
      foreach (($comp['types'] ?? []) as $t) {
        $map[$t] = $comp;
      }
    }
    $street = trim(($map['street_number']['long_name'] ?? '') . ' ' . ($map['route']['long_name'] ?? ''));
    $city = (string)($map['locality']['long_name'] ?? $map['postal_town']['long_name'] ?? $map['sublocality']['long_name'] ?? '');
    $region = (string)($map['administrative_area_level_1']['short_name'] ?? $map['administrative_area_level_1']['long_name'] ?? '');
    $postal = (string)($map['postal_code']['long_name'] ?? '');
    $cc = strtoupper((string)($map['country']['short_name'] ?? ''));
    return [$street,$city,$region,$postal,$cc];
  }
  public function normalize(string $raw): AddressData{
    $data = $this->client->geocode($raw);
    $first = ($data['results'] ?? [])[0] ?? null;
    if (!$first) { return new AddressData('','','','',''); }
    [$street,$city,$region,$postal,$cc] = self::mapAddressComponents($first['address_components'] ?? []);
    return new AddressData($street,$city,$region,$postal,$cc);
  }
  public function geocode(AddressData $a): GeoPoint{
    $q = trim($a->street . ', ' . $a->city . ', ' . $a->region . ' ' . $a->postalCode . ', ' . $a->countryCode);
    $data = $this->client->geocode($q);
    $first = ($data['results'] ?? [])[0] ?? null;
    if (!$first) { return new GeoPoint(0.0,0.0); }
    $loc = $first['geometry']['location'] ?? ['lat'=>0,'lng'=>0];
    return new GeoPoint((float)$loc['lat'], (float)$loc['lng']);
  }
  public function reverse(GeoPoint $p): AddressData{
    $data = $this->client->reverse($p->latitude, $p->longitude);
    $first = ($data['results'] ?? [])[0] ?? null;
    if (!$first) { return new AddressData('','','','',''); }
    [$street,$city,$region,$postal,$cc] = self::mapAddressComponents($first['address_components'] ?? []);
    return new AddressData($street,$city,$region,$postal,$cc);
  }
}
