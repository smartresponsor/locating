<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\EntityInterface\Locator\AddressSuggestionInterface;
use Smartresponsor\ServiceInterface\Locator\AddressSuggestRankerInterface;

/**
 * Default implementation of AddressSuggestRankerInterface.
 *
 * This ranker is intentionally simple but still good enough for
 * production use in small and medium deployments. It combines:
 *
 * - fuzzy match between query and suggestion label;
 * - bias for explicit countryCode parameter;
 * - bias derived from locale (e.g. de_DE -> DE);
 * - a small preference for shorter, cleaner labels.
 */
final class AddressSuggestRanker implements AddressSuggestRankerInterface
{
    /**
     * @param AddressSuggestionInterface[] $items
     * @return AddressSuggestionInterface[]
     */
    public function rank(string $query, ?string $countryCode, ?string $locale, array $items): array
    {
        if ($items === []) {
            return [];
        }

        $normalizedQuery = $this->normalize($query);
        $countryCode = $countryCode !== null && $countryCode !== '' ? strtoupper($countryCode) : null;
        $localeCountry = $this->countryFromLocale($locale);

        $scored = [];

        foreach ($items as $index => $item) {
            if (!$item instanceof AddressSuggestionInterface) {
                continue;
            }

            $label = $item->label();
            $addressData = $item->addressData();

            $suggestCountry = '';
            if (property_exists($addressData, 'countryCode')) {
                /** @var mixed $country */
                $country = $addressData->countryCode;
                if (is_string($country)) {
                    $suggestCountry = strtoupper($country);
                }
            }

            $score = 0.0;

            // Core fuzzy score on the label.
            $score += $this->fuzzyScore($normalizedQuery, $this->normalize($label));

            // Bias for explicit country code match.
            if ($countryCode !== null && $suggestCountry !== '' && $suggestCountry === $countryCode) {
                $score += 0.2;
            }

            // Bias for locale-derived country match.
            if ($localeCountry !== null && $suggestCountry !== '' && $suggestCountry === $localeCountry) {
                $score += 0.1;
            }

            // Prefer shorter labels slightly to avoid noisy suggestions.
            $lengthPenalty = min(strlen($label) / 1000.0, 0.1);
            $score -= $lengthPenalty;

            $scored[] = [
                'index' => $index,
                'item' => $item,
                'score' => $score,
            ];
        }

        usort(
            $scored,
            static function (array $a, array $b): int {
                if ($a['score'] === $b['score']) {
                    return $a['index'] <=> $b['index'];
                }

                return $a['score'] < $b['score'] ? 1 : -1;
            }
        );

        $result = [];
        foreach ($scored as $row) {
            $result[] = $row['item'];
        }

        return $result;
    }

    private function normalize(string $value): string
    {
        $value = strtolower(trim($value));
        $value = (string)preg_replace('/[^a-z0-9\p{L}\s]+/u', ' ', $value);
        $value = (string)preg_replace('/\s+/', ' ', $value);

        return $value;
    }

    private function fuzzyScore(string $query, string $label): float
    {
        if ($query === '' || $label === '') {
            return 0.0;
        }

        if ($query === $label) {
            return 1.0;
        }

        $base = 0.0;
        if (function_exists('similar_text')) {
            $percent = 0.0;
            similar_text($query, $label, $percent);
            $base = max(0.0, min(1.0, $percent / 100.0));
        }

        $tokensQuery = array_values(array_filter(explode(' ', $query)));
        $tokensLabel = array_values(array_filter(explode(' ', $label)));

        $setQuery = array_values(array_unique($tokensQuery));
        $setLabel = array_values(array_unique($tokensLabel));

        $intersection = array_values(array_intersect($setQuery, $setLabel));
        $union = array_values(array_unique(array_merge($setQuery, $setLabel)));

        $tokenScore = 0.0;
        if ($union !== []) {
            $tokenScore = count($intersection) / count($union);
        }

        $score = 0.7 * $base + 0.3 * $tokenScore;

        return max(0.0, min(1.0, $score));
    }

    private function countryFromLocale(?string $locale): ?string
    {
        if ($locale === null || $locale === '') {
            return null;
        }

        $normalized = str_replace('-', '_', $locale);
        $parts = explode('_', $normalized);

        if (count($parts) === 2 && $parts[1] !== '') {
            return strtoupper($parts[1]);
        }

        $language = strtolower($parts[0]);

        return match ($language) {
            'en' => 'US',
            'uk' => 'UA',
            'ru' => 'RU',
            'de' => 'DE',
            'fr' => 'FR',
            'es' => 'ES',
            'it' => 'IT',
            'nl' => 'NL',
            default => null,
        };
    }
}
