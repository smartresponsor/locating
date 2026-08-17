<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\ProviderRankerServiceInterface;

final class ProviderRankerService implements ProviderRankerServiceInterface
{
    /**
     * @param list<array<string, mixed>> $items
     * @return list<array<string, mixed>>
     */
    public static function sort(array $items): array
    {
        usort(
            $items,
            static function (array $left, array $right): int {
                $leftScore = is_numeric($left['confidence'] ?? null) ? (float) $left['confidence'] : 0.0;
                $rightScore = is_numeric($right['confidence'] ?? null) ? (float) $right['confidence'] : 0.0;

                if ($leftScore === $rightScore) {
                    return 0;
                }

                return $leftScore > $rightScore ? -1 : 1;
            }
        );

        return $items;
    }
}
