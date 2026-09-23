<?php

declare(strict_types=1);

namespace App\Locating\Tests\Locator;

use App\Locating\Service\Provider\Location\Resilience\Hedger;
use PHPUnit\Framework\TestCase;

final class HedgerFailureTest extends TestCase
{
    public function testSecondaryFailureIsNotSwallowedWhenPrimaryReturnsEmptyResult(): void
    {
        $secondaryFailure = new \RuntimeException('secondary failed');

        $this->expectExceptionObject($secondaryFailure);

        Hedger::race(
            [
                static fn (): array => [],
                static function () use ($secondaryFailure): array {
                    throw $secondaryFailure;
                },
            ],
            0,
        );
    }

    public function testDualFailurePreservesBothFailureContexts(): void
    {
        $primaryFailure = new \RuntimeException('primary failed');
        $secondaryFailure = new \RuntimeException('secondary failed');

        /** @var list<callable(): array<int>> $callables */
        $callables = [
            static function () use ($primaryFailure): array {
                throw $primaryFailure;
            },
            static function () use ($secondaryFailure): array {
                throw $secondaryFailure;
            },
        ];

        try {
            Hedger::race($callables, 0);
            self::fail('Expected dual hedge failure.');
        } catch (\RuntimeException $failure) {
            self::assertStringContainsString('primary failed', $failure->getMessage());
            self::assertSame($secondaryFailure, $failure->getPrevious());
        }
    }
}
