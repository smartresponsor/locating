<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionRankerInterface;

final class AddressSuggestionRanker implements AddressSuggestionRankerInterface
{
    public function rank(string $query, ?string $countryCode, array $items): array
    {
        if ([] === $items) {
            return [];
        }

        $normalizedQuery = $this->normalize($query);
        $normalizedCountryCode = null !== $countryCode && '' !== $countryCode ? strtoupper($countryCode) : null;

        $scored = [];

        foreach ($items as $index => $item) {
            $label = $item->label();
            $normalizedLabel = $this->normalize($label);
            $score = $this->fuzzyScore($normalizedQuery, $normalizedLabel);

            $itemCountryCode = strtoupper($item->address()->countryCode());
            if (null !== $normalizedCountryCode && '' !== $itemCountryCode && $itemCountryCode === $normalizedCountryCode) {
                $score += 0.2;
            }

            $score -= min(strlen($label) / 1000.0, 0.1);

            $scored[] = [
                'index' => $index,
                'score' => $score,
                'item' => $item,
            ];
        }

        usort(
            $scored,
            static function (array $left, array $right): int {
                if ($left['score'] === $right['score']) {
                    return $left['index'] <=> $right['index'];
                }

                return $left['score'] < $right['score'] ? 1 : -1;
            }
        );

        return array_map(static fn (array $row): AddressSuggestionResultInterface => $row['item'], $scored);
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

        $base = 0.0;
        if (function_exists('similar_text')) {
            $percent = 0.0;
            similar_text($query, $label, $percent);
            $base = max(0.0, min(1.0, $percent / 100.0));
        }

        $queryTokens = array_values(array_filter(explode(' ', $query)));
        $labelTokens = array_values(array_filter(explode(' ', $label)));

        $intersection = array_values(array_intersect(array_unique($queryTokens), array_unique($labelTokens)));
        $union = array_values(array_unique(array_merge($queryTokens, $labelTokens)));

        $tokenScore = [] === $union ? 0.0 : count($intersection) / count($union);

        return max(0.0, min(1.0, (0.7 * $base) + (0.3 * $tokenScore)));
    }
}
