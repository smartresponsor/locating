<?php

declare(strict_types=1);

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\ServiceInterface\Address\Location\AddressHintBiasServiceInterface;

final class AddressHintBiasService implements AddressHintBiasServiceInterface
{
    /** Apply simple bias to provider/region choice given hints. */
    public function region(array $hint): string
    {
        $country = strtoupper((string) ($hint['country'] ?? ''));

        $region = match ($country) {
            'US', 'CA' => 'us',
            'GB', 'UK', 'DE', 'FR', 'ES', 'IT', 'NL' => 'eu',
            'AU', 'NZ' => 'apac',
            default => 'us',
        };

        return (string) ($hint['region'] ?? $region);
    }

    public function locale(array $hint): string
    {
        $locale = (string) ($hint['locale'] ?? '');
        if ('' !== $locale) {
            return $locale;
        }

        return 'en';
    }
}
