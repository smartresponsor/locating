<?php

declare(strict_types=1);

namespace App\Locating\Tests\Locator;

use App\Locating\Integration\Provider\Location\Decorator\TryFallbackLocator;
use App\Locating\Model\Location\AddressData;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocatorInterface;
use PHPUnit\Framework\TestCase;

final class TryFallbackLocatorFailureTest extends TestCase
{
    public function testPrimaryFailureFallsBackToSecondary(): void
    {
        $expected = new AddressData('1 Main St', 'Houston', 'TX', '77002', 'US');
        $primary = $this->createStub(LocatorInterface::class);
        $primary->method('normalize')->willThrowException(new \RuntimeException('primary failed'));

        $secondary = $this->createStub(LocatorInterface::class);
        $secondary->method('normalize')->willReturn($expected);

        $locator = new TryFallbackLocator($primary, $secondary);

        self::assertSame($expected, $locator->normalize('raw address'));
    }

    public function testDualNormalizeFailurePreservesPrimaryAndSecondaryContexts(): void
    {
        $primaryFailure = new \RuntimeException('primary failed');
        $secondaryFailure = new \RuntimeException('secondary failed');
        $primary = $this->createStub(LocatorInterface::class);
        $primary->method('normalize')->willThrowException($primaryFailure);

        $secondary = $this->createStub(LocatorInterface::class);
        $secondary->method('normalize')->willThrowException($secondaryFailure);

        $locator = new TryFallbackLocator($primary, $secondary);

        try {
            $locator->normalize('raw address');
            self::fail('Expected dual locator failure.');
        } catch (\RuntimeException $failure) {
            self::assertStringContainsString('primary failed', $failure->getMessage());
            self::assertSame($secondaryFailure, $failure->getPrevious());
        }
    }
}
