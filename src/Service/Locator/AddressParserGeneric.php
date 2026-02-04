<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Service\Locator;

use App\Entity\Locator\AddressData;
use App\EntityInterface\Locator\AddressDataInterface;
use App\EntityInterface\Locator\AddressInputInterface;
use App\ServiceInterface\Locator\AddressParserCountryStrategyInterface;

final class AddressParserGeneric implements AddressParserCountryStrategyInterface
{
    public function supportCountryCode(?string $countryCode): bool
    {
        return true;
    }

    public function parse(AddressInputInterface $input): AddressDataInterface
    {
        $rawLine = $input->rawLine();
        $trimmed = \trim($rawLine);

        if ($trimmed === '') {
            return new AddressData(null, null, null, null, null, null, null);
        }

        $parts = \array_values(
            \array_filter(
                \preg_split('/[,\t]+/', $trimmed),
                static fn (string $part): bool => \trim($part) !== ''
            )
        );

        $street = null;
        $house = null;
        $unit = null;
        $city = null;
        $region = null;
        $postalCode = null;
        $countryCode = $input->countryCode();

        if (\count($parts) > 0) {
            $street = \trim($parts[0]);
        }
        if (\count($parts) > 1) {
            $city = \trim($parts[1]);
        }
        if (\count($parts) > 2) {
            $region = \trim($parts[2]);
        }
        if (\count($parts) > 3) {
            $postalCode = \trim($parts[3]);
        }

        return new AddressData(
            $countryCode,
            $region,
            $city,
            $postalCode,
            $street,
            $house,
            $unit
        );
    }
}

