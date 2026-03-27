<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 */

namespace App\Service;

use App\Entity\AddressData;
use App\EntityInterface\AddressDataInterface;
use App\EntityInterface\AddressInputInterface;
use App\ServiceInterface\AddressParserCountryStrategyInterface;

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
            return new AddressData('', '', '', '', '');
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
            $street ?? '',
            $city ?? '',
            $region ?? '',
            $postalCode ?? '',
            $countryCode ?? '',
            $house,
            $unit
        );
    }
}
