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
    /** @var array<string, array<string, float>> */
    private array $weight = [];

    public function set(string $tag, string $region, float $weight): void
    {
        $this->weight[$tag][$region] = max(0.0, min(2.0, $weight));
    }

    /** @param array<array-key, mixed> $hint */
    public function weight(array $hint, string $region): float
    {
        $multiplier = 1.0;
        foreach ($hint as $tag) {
            if (!is_string($tag)) {
                continue;
            }
            $multiplier *= $this->weight[$tag][$region] ?? 1.0;
        }

        return max(0.25, min(4.0, $multiplier));
    }

    /**
     * Apply simple bias to provider/region choice given hints.
     *
     * @param array<string, mixed> $hint
     */
    public function region(array $hint): string
    {
        $countryValue = $hint['country'] ?? null;
        $country = is_string($countryValue) ? strtoupper($countryValue) : '';

        $region = match ($country) {
            'US', 'CA' => 'us',
            'GB', 'UK', 'DE', 'FR', 'ES', 'IT', 'NL' => 'eu',
            'AU', 'NZ' => 'apac',
            default => 'us',
        };
        $regionValue = $hint['region'] ?? null;

        return is_string($regionValue) && '' !== $regionValue ? $regionValue : $region;
    }

    /** @param array<string, mixed> $hint */
    public function locale(array $hint): string
    {
        $locale = $hint['locale'] ?? null;

        return is_string($locale) && '' !== $locale ? $locale : 'en';
    }
}
