<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\Entity\Locator\AddressSuggestion;
use Smartresponsor\EntityInterface\Locator\AddressSuggestionInterface;
use Smartresponsor\ServiceInterface\Locator\SuggestRankerInterface;

final class SuggestRanker implements SuggestRankerInterface
{
    /**
     * @param AddressSuggestionInterface[] $suggestList
     * @return AddressSuggestionInterface[]
     */
    public function rank(string $query, array $suggestList, ?string $countryCode): array
    {
        $normalizedQuery = mb_strtolower(trim($query));
        if ($normalizedQuery === '') {
            return $suggestList;
        }

        $scoredList = [];

        foreach ($suggestList as $suggest) {
            if (!$suggest instanceof AddressSuggestion) {
                continue;
            }

            $label = mb_strtolower($suggest->label());
            $score = 0.0;
            $reason = [];

            if (str_starts_with($label, $normalizedQuery)) {
                $score += 3.0;
                $reason['prefix'] = 3.0;
            } elseif (mb_strpos($label, ' ' . $normalizedQuery) !== false) {
                $score += 2.0;
                $reason['word'] = 2.0;
            } elseif (mb_strpos($label, $normalizedQuery) !== false) {
                $score += 1.0;
                $reason['substring'] = 1.0;
            }

            if ($countryCode !== null && $countryCode !== '') {
                $normalizedCountry = mb_strtolower($countryCode);
                $addressArray = $suggest->addressData()->toArray();
                $suggestCountry = '';
                if (isset($addressArray['countryCode'])) {
                    $suggestCountry = mb_strtolower((string) $addressArray['countryCode']);
                }

                if ($suggestCountry === $normalizedCountry) {
                    $score += 1.5;
                    $reason['country'] = 1.5;
                }
            }

            $noiseMatch = [];
            $noiseCount = preg_match_all('/[^a-z0-9\s]/u', $label, $noiseMatch) ?: 0;
            if ($noiseCount > 5) {
                $score -= 0.5;
                $reason['noise'] = -0.5;
            }

            $scoredList[] = $suggest->withScore($score, $reason);
        }

        usort(
            $scoredList,
            static function (AddressSuggestion $left, AddressSuggestion $right): int {
                $leftScore = $left->score() ?? 0.0;
                $rightScore = $right->score() ?? 0.0;

                if ($leftScore === $rightScore) {
                    return strcmp($left->label(), $right->label());
                }

                return $leftScore < $rightScore ? 1 : -1;
            }
        );

        return $scoredList;
    }
}

