<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\ProviderCostSignal;
use PHPUnit\Framework\TestCase;

final class ProviderCostSignalTest extends TestCase
{
    public function testToArrayExposesCanonicalSignalShape(): void
    {
        $signal = new ProviderCostSignal('primary', 'suggest', 'US', 0.35);

        self::assertSame('primary', $signal->sourceKey());
        self::assertSame('suggest', $signal->operation());
        self::assertSame('US', $signal->region());
        self::assertSame(0.35, $signal->unitCost());
        self::assertSame([
            'sourceKey' => 'primary',
            'operation' => 'suggest',
            'region' => 'US',
            'unitCost' => 0.35,
        ], $signal->toArray());
    }
}
