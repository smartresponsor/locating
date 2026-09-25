<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Provider\Location;

use App\Locating\ModelInterface\Location\AddressReverseResultInterface;
use App\Locating\NormalizerInterface\Provider\Location\AddressReverseResultNormalizerInterface;
use App\Locating\ProviderInterface\Location\AddressReverseProviderInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceOrderInterface;

final class OrderedAddressReverseProvider implements AddressReverseProviderInterface
{
    /** @var list<AddressReverseSourceInterface> */
    private array $sources = [];

    /**
     * @param iterable<AddressReverseSourceInterface> $sources
     */
    public function __construct(
        iterable $sources,
        private readonly AddressReverseSourceOrderInterface $sourceOrder,
        private readonly AddressReverseResultNormalizerInterface $resultNormalizer,
    ) {
        foreach ($sources as $source) {
            $this->sources[] = $source;
        }
    }

    public function reverse(float $latitude, float $longitude, ?string $countryCode = null): AddressReverseResultInterface
    {
        foreach ($this->sourceOrder->order($this->sources, $latitude, $longitude, $countryCode) as $source) {
            $result = $source->reverse($latitude, $longitude, $countryCode);
            if ('rejected' !== $result->status()) {
                return $this->resultNormalizer->normalize($result, $latitude, $longitude, $countryCode);
            }
        }

        if ([] === $this->sources) {
            throw new \RuntimeException('No address reverse sources configured.');
        }

        $fallback = $this->sources[0]->reverse($latitude, $longitude, $countryCode);

        return $this->resultNormalizer->normalize($fallback, $latitude, $longitude, $countryCode);
    }
}
