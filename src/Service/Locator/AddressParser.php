<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\Entity\Locator\AddressInput;
use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\ServiceInterface\Locator\AddressParserInterface;

/**
 * Simple parser that prefers structured data and falls back to raw line heuristics.
 */
final class AddressParser implements AddressParserInterface
{
    public function parse(AddressInput $input): AddressData
    {
        $data = $input->data();

        if (!empty($data)) {
            return AddressData::fromArray($data);
        }

        $raw = trim($input->rawLine());
        if ($raw === '') {
            return new AddressData('', '', '', '', '');
        }

        $parts = array_map('trim', explode(',', $raw));
        $street = $parts[0] ?? '';
        $city = $parts[1] ?? '';
        $region = $parts[2] ?? '';
        $postalCode = $parts[3] ?? '';
        $countryCode = $parts[4] ?? '';

        return new AddressData($street, $city, $region, $postalCode, strtoupper($countryCode));
    }
}
