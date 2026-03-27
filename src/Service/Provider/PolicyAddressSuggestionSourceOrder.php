<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Provider\Location;

use App\ServiceInterface\Provider\Location\AddressSuggestionSourceCostPolicyInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceHealthPolicyInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceOrderInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceQuotaPolicyInterface;

final class PolicyAddressSuggestionSourceOrder implements AddressSuggestionSourceOrderInterface
{
    public function __construct(
        private readonly AddressSuggestionSourceHealthPolicyInterface $healthPolicy,
        private readonly AddressSuggestionSourceQuotaPolicyInterface $quotaPolicy,
        private readonly AddressSuggestionSourceCostPolicyInterface $costPolicy,
    ) {
    }

    public function order(iterable $sources, string $query, ?string $countryCode = null, int $limit = 5): array
    {
        $eligible = [];

        foreach ($sources as $index => $source) {
            if (!$source instanceof AddressSuggestionSourceInterface) {
                continue;
            }

            if (!$this->quotaPolicy->allows($source->sourceKey(), $query, $countryCode, $limit)) {
                continue;
            }

            $eligible[] = [
                'index' => (int) $index,
                'score' => $this->healthPolicy->score($source->sourceKey()),
                'costPenalty' => $this->costPolicy->penalty($source->sourceKey(), $query, $countryCode, $limit),
                'source' => $source,
            ];
        }

        usort(
            $eligible,
            static function (array $left, array $right): int {
                if ($left['score'] === $right['score']) {
                    if ($left['costPenalty'] === $right['costPenalty']) {
                        return $left['index'] <=> $right['index'];
                    }

                    return $left['costPenalty'] <=> $right['costPenalty'];
                }

                return $left['score'] < $right['score'] ? 1 : -1;
            }
        );

        return array_map(static fn (array $row): AddressSuggestionSourceInterface => $row['source'], $eligible);
    }
}
