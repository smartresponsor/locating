<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Provider\Location;

use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceCostPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceHealthPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceOrderInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceQuotaPolicyInterface;

final class PolicyAddressReverseSourceOrder implements AddressReverseSourceOrderInterface
{
    public function __construct(
        private readonly AddressReverseSourceHealthPolicyInterface $healthPolicy,
        private readonly AddressReverseSourceQuotaPolicyInterface $quotaPolicy,
        private readonly AddressReverseSourceCostPolicyInterface $costPolicy,
    ) {
    }

    public function order(iterable $sources, float $latitude, float $longitude, ?string $countryCode = null): array
    {
        $eligible = [];
        $index = 0;

        foreach ($sources as $source) {
            if (!$this->quotaPolicy->allows($source->sourceKey(), $latitude, $longitude, $countryCode)) {
                ++$index;
                continue;
            }

            $eligible[] = [
                'index' => $index++,
                'score' => $this->healthPolicy->score($source->sourceKey()),
                'costPenalty' => $this->costPolicy->penalty($source->sourceKey(), $latitude, $longitude, $countryCode),
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

        return array_map(static fn (array $row): AddressReverseSourceInterface => $row['source'], $eligible);
    }
}
