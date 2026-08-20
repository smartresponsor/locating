<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location\Runtime\Geo;

use App\Locating\Service\Provider\Location\Runtime\Geo\LocationDistanceService;
use PHPUnit\Framework\TestCase;

final class LocationDistanceServiceTest extends TestCase
{
    public function testSamePointHasZeroDistance(): void
    {
        $service = new LocationDistanceService();

        self::assertSame(0.0, $service->meters(29.7604, -95.3698, 29.7604, -95.3698));
    }

    public function testOneLongitudeDegreeAtEquatorIsAboutOneHundredElevenKilometers(): void
    {
        $service = new LocationDistanceService();
        $distance = $service->meters(0.0, 0.0, 0.0, 1.0);

        self::assertGreaterThan(111000.0, $distance);
        self::assertLessThan(111300.0, $distance);
    }
}
