<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Provider\Location;

use App\Entity\Location\AddressReverseResult;
use App\Entity\Location\AddressView;
use App\EntityInterface\Location\AddressReverseResultInterface;
use App\ServiceInterface\Provider\Location\AddressReverseResultNormalizerInterface;

final class AddressReverseResultNormalizer implements AddressReverseResultNormalizerInterface
{
    public function normalize(AddressReverseResultInterface $result, float $latitude, float $longitude, ?string $countryCode = null): AddressReverseResultInterface
    {
        $address = $result->address();
        $normalizedAddress = null;
        if (null !== $address) {
            $normalizedAddress = new AddressView(
                trim($address->street()),
                trim($address->city()),
                trim($address->region()),
                trim($address->postalCode()),
                strtoupper(trim('' !== $address->countryCode() ? $address->countryCode() : (string) ($countryCode ?? ''))),
            );
        }

        $geoPoint = $result->geoPoint() ?? [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ];

        $providerKey = $result->providerKey();
        if (null === $providerKey || '' === $providerKey) {
            $providerKey = 'reverse';
        }

        return new AddressReverseResult(
            $result->status(),
            $normalizedAddress,
            $result->issues(),
            $geoPoint,
            $providerKey,
        );
    }
}
