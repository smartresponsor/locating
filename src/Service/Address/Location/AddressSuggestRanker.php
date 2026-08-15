<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;
use App\Locating\ServiceInterface\Address\Location\AddressSuggestRankerInterface;

/**
 * Rank suggestions according to fuzzy match and locale/country hints.
 */
final class AddressSuggestRanker implements AddressSuggestRankerInterface
{
    /**
     * @param AddressSuggestionResultInterface[] $items
     *
     * @return AddressSuggestionResultInterface[]
     */
    public function rank(string $query, ?string $countryCode, ?string $locale, array $items): array
    {
        if ([] === $items) {
            return [];
        }

        $normalizedQuery = $this->normalize($query);
        $countryCode = null !== $countryCode && '' !== $countryCode ? strtoupper($countryCode) : null;
        $localeCountry = $this->countryFromLocale($locale);
        $scored = [];

        foreach ($items as $index => $item) {
            if (!$item instanceof AddressSuggestionResultInterface) {
                continue;
            }

            $label = $item->label();
            $addressData = $item->addressData();
            $suggestCountry = '';
            if (property_exists($addressData, 'countryCode')) {
                $country = $addressData->countryCode;
                if (is_string($country)) {
                    $suggestCountry = strtoupper($country);
                }
            }

            $score = 0.0;
            $score += $this->fuzzyScore($normalizedQuery, $this->normalize($label));
            if (null !== $countryCode && '' !== $suggestCountry && $suggestCountry === $countryCode) {
                $score += 0.2;
            }
            if (null !== $localeCountry && '' !== $suggestCountry && $suggestCountry === $localeCountry) {
                $score += null === $countryCode ? 1.0 : 0.1;
            }
            $score -= min(strlen($label) / 1000.0, 0.1);
            $scored[] = ['index' => $index, 'item' => $item, 'score' => $score];
        }

        usort($scored, static function (array $a, array $b): int {
            if ($a['score'] === $b['score']) {
                return $a['index'] <=> $b['index'];
            }

            return $a['score'] < $b['score'] ? 1 : -1;
        });

        return array_values(array_map(static fn (array $row) => $row['item'], $scored));
    }

    private function normalize(string $value): string
    {
        $value = strtolower(trim($value));
        $value = (string) preg_replace('/[^a-z0-9\p{L}\s]+/u', ' ', $value);
        $value = (string) preg_replace('/\s+/', ' ', $value);

        return $value;
    }

    private function fuzzyScore(string $query, string $label): float
    {
        if ('' === $query || '' === $label) {
            return 0.0;
        }
        if ($query === $label) {
            return 1.0;
        }
        $percent = 0.0;
        similar_text($query, $label, $percent);

        return max(0.0, min(1.0, $percent / 100.0));
    }

    private function countryFromLocale(?string $locale): ?string
    {
        if (null === $locale || '' === $locale) {
            return null;
        }
        $normalized = str_replace('-', '_', $locale);
        $parts = explode('_', $normalized);
        if (2 === count($parts) && '' !== $parts[1]) {
            return strtoupper($parts[1]);
        }

        return null;
    }
}
