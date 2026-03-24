<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\AddressData;
use App\Entity\AddressSuggestion;
use App\Service\AddressSuggestRanker;
use PHPUnit\Framework\TestCase;

final class AddressSuggestRankerTest extends TestCase
{
    public function testRankPrefersCloserLabel(): void
    {
        $ranker = new AddressSuggestRanker();

        $data = new AddressData('Main Street 1', 'City', 'Region', '12345', 'US');

        $first = new AddressSuggestion('Main Street 99', $data, 'test');
        $second = new AddressSuggestion('Main Street 1', $data, 'test');

        $items = [$first, $second];

        $ranked = $ranker->rank('Main Street 1', 'US', 'en_US', $items);

        self::assertSame('Main Street 1', $ranked[0]->label());
    }

    public function testRankUsesLocaleBiasWhenCountryIsMissing(): void
    {
        $ranker = new AddressSuggestRanker();

        $dataUs = new AddressData('Main Street 1', 'City', 'Region', '12345', 'US');
        $dataDe = new AddressData('Main Street 1', 'Berlin', 'BE', '10115', 'DE');

        $us = new AddressSuggestion('Main Street 1', $dataUs, 'test');
        $de = new AddressSuggestion('Main Street 1', $dataDe, 'test');

        $ranked = $ranker->rank('Main', null, 'de_DE', [$us, $de]);

        self::assertSame('DE', $ranked[0]->addressData()->countryCode);
    }
}
