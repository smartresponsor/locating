<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Address\Location;

use App\Entity\Location\AddressView;
use App\EntityInterface\Location\AddressViewInterface;
use App\ServiceInterface\Address\Location\AddressNormalizerInterface;

final class AddressNormalizer implements AddressNormalizerInterface
{
    public function normalize(AddressViewInterface $address): AddressViewInterface
    {
        $normalized = [];

        foreach ($address->toArray() as $key => $value) {
            $clean = trim((string) preg_replace('/\s+/', ' ', (string) $value));
            if ('countryCode' === $key) {
                $clean = strtoupper($clean);
            }
            $normalized[$key] = $clean;
        }

        return AddressView::fromArray($normalized);
    }
}
