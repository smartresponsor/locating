<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Service;

use App\Entity\AddressData;
use App\Entity\AddressInput;
use App\ServiceInterface\AddressParserInterface;

/**
 * Simple parser that prefers structured data and falls back to raw line heuristics.
 */
final class AddressParser implements AddressParserInterface
{
    /**
     * @var array<int, object>
     */
    private array $strategyList;

    /**
     * @param iterable<object> $strategies
     */
    public function __construct(iterable $strategies = [])
    {
        $this->strategyList = is_array($strategies) ? array_values($strategies) : iterator_to_array($strategies, false);
    }

    public function parse(AddressInput $input): AddressData
    {
        foreach ($this->strategyList as $strategy) {
            if (
                method_exists($strategy, 'supportCountryCode') &&
                method_exists($strategy, 'parse') &&
                $strategy->supportCountryCode($input->countryCode())
            ) {
                $parsed = $strategy->parse($input);

                if ($parsed instanceof AddressData) {
                    return $parsed;
                }
            }
        }

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
