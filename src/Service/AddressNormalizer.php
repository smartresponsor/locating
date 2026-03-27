<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Service;

use App\Entity\AddressData;
use App\ServiceInterface\AddressNormalizerInterface;

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
