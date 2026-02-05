<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\ServiceInterface\Locator\AddressNormalizerInterface;

final class AddressNormalizer implements AddressNormalizerInterface
{
    public function normalize(AddressData $address): AddressData
    {
        $data = $address->toArray();

        foreach ($data as $key => $value) {
            if (!is_string($value)) {
                continue;
            }
            $value = trim(preg_replace('/\s+/', ' ', $value));
            if ($key === 'countryCode') {
                $value = strtoupper($value);
            }
            $data[$key] = $value;
        }

        return AddressData::fromArray($data);
    }
}
