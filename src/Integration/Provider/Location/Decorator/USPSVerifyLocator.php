<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Decorator;

use App\Locating\Integration\Provider\Location\Http\USPSClient;
use App\Locating\Integration\Provider\Location\Provider\USPS\USPSFormatter;
use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\GeoPoint;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocatorInterface;

final class USPSVerifyLocator implements LocatorInterface
{
    public function __construct(private LocatorInterface $inner, private USPSClient $client)
    {
    }

    /** @return array{0:string,1:string,2:string,3:string,4:string}|null */
    private static function tryParseUSLine(string $raw): ?array
    {
        // naive parser: "street, city, ST ZIP" or "street, city, ST"
        $parts = array_map('trim', explode(',', $raw));
        if (count($parts) < 3) {
            return null;
        }
        $street = $parts[0];
        $city = $parts[1];
        $rest = $parts[2];
        if (!preg_match('~^([A-Z]{2})(?:\s+(\d{5})(?:-(\d{4}))?)?$~i', $rest, $m)) {
            return null;
        }
        $state = strtoupper($m[1]);
        $zip5 = isset($m[2]) ? $m[2] : '';
        $zip4 = isset($m[3]) ? $m[3] : '';

        return [$street, $city, $state, $zip5, $zip4];
    }

    public function normalize(string $raw): AddressData
    {
        $parsed = self::tryParseUSLine($raw);
        if ($parsed) {
            try {
                [$street,$city,$state,$zip5,$zip4] = $parsed;
                $v = $this->client->verify($street, $city, $state, $zip5, $zip4);

                return USPSFormatter::toAddress($v);
            } catch (\Throwable $e) { /* fallback */
            }
        }
        // fallback to inner
        $a = $this->inner->normalize($raw);
        // post-verify for US addresses
        if ('US' === strtoupper($a->countryCode) && '' !== $a->region) {
            try {
                // region is state
                $zip5 = preg_replace('~[^0-9]~', '', $a->postalCode);
                $zip5 = substr($zip5 ?? '', 0, 5);
                $v = $this->client->verify($a->street, $a->city, $a->region, $zip5 ?: '', '');

                return USPSFormatter::toAddress($v);
            } catch (\Throwable $e) { /* keep a */
            }
        }

        return $a;
    }

    public function geocode(AddressData $a): GeoPoint
    {
        if ('US' === strtoupper($a->countryCode) && '' !== $a->region) {
            try {
                $zip5 = preg_replace('~[^0-9]~', '', $a->postalCode);
                $zip5 = substr($zip5 ?? '', 0, 5);
                $v = $this->client->verify($a->street, $a->city, $a->region, $zip5 ?: '', '');
                $a = USPSFormatter::toAddress($v);
            } catch (\Throwable $e) { /* fallback to inner geocode */
            }
        }

        return $this->inner->geocode($a);
    }

    public function reverse(GeoPoint $p): AddressData
    {
        $a = $this->inner->reverse($p);
        if ('US' === strtoupper($a->countryCode) && '' !== $a->region) {
            try {
                $zip5 = preg_replace('~[^0-9]~', '', $a->postalCode);
                $zip5 = substr($zip5 ?? '', 0, 5);
                $v = $this->client->verify($a->street, $a->city, $a->region, $zip5 ?: '', '');

                return USPSFormatter::toAddress($v);
            } catch (\Throwable $e) { /* keep a */
            }
        }

        return $a;
    }
}
