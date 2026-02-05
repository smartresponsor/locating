<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Tests\Locator\Service;

use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressSuggestion;
use Smartresponsor\Service\Locator\SuggestRanker;
use PHPUnit\Framework\TestCase;

final class SuggestRankerTest extends TestCase
{
    private function createSuggestion(string $label, string $countryCode): AddressSuggestion
    {
        $address = new AddressData(
            street: $label,
            city: 'City',
            region: 'Region',
            postalCode: '00000',
            countryCode: $countryCode
        );

        return new AddressSuggestion($label, $address, 'provider-demo');
    }

    public function testPrefixMatchHasHigherScoreThanSubstring(): void
    {
        $ranker = new SuggestRanker();

        $query = 'main';

        $prefix = $this->createSuggestion('Main Street', 'US');
        $substring = $this->createSuggestion('Old Domain Road', 'US');

        $ranked = $ranker->rank($query, [$substring, $prefix], 'US');

        self::assertSame('Main Street', $ranked[0]->label());
    }

    public function testCountryBoostApplied(): void
    {
        $ranker = new SuggestRanker();

        $query = 'main';

        $us = $this->createSuggestion('Main Street', 'US');
        $gb = $this->createSuggestion('Main Street', 'GB');

        $ranked = $ranker->rank($query, [$gb, $us], 'US');

        self::assertSame('US', $ranked[0]->addressData()->toArray()['countryCode'] ?? null);
    }

    public function testScoreAndReasonSet(): void
    {
        $ranker = new SuggestRanker();

        $query = 'main';

        $suggestion = $this->createSuggestion('Main Avenue', 'US');

        $ranked = $ranker->rank($query, [$suggestion], 'US');

        self::assertCount(1, $ranked);
        self::assertNotNull($ranked[0]->score());
        self::assertNotSame([], $ranked[0]->rankReason());
    }
}

