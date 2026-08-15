<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Provider\USPS;

use App\Locating\Model\Location\AddressData;

final class USPSFormatter
{
    public static function formatZip(string $zip5, string $zip4): string
    {
        return $zip4 !== '' ? ($zip5 . '-' . $zip4) : $zip5;
    }
    public static function toAddress(array $verified): AddressData
    {
        return new AddressData(
            $verified['street'] ?? '',
            $verified['city'] ?? '',
            $verified['state'] ?? '',
            self::formatZip($verified['zip5'] ?? '', $verified['zip4'] ?? ''),
            'US'
        );
    }
}
