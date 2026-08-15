<?php

declare(strict_types=1);

namespace App\Locating\Tests\Entity\Location;

use App\Locating\Model\Location\ProviderHealthSignal;
use PHPUnit\Framework\TestCase;

final class ProviderHealthSignalTest extends TestCase
{
    public function testItExposesTypedValues(): void
    {
        $signal = new ProviderHealthSignal('primary', 0.97, 123.4);

        self::assertSame('primary', $signal->sourceKey());
        self::assertSame(0.97, $signal->successRate());
        self::assertSame(123.4, $signal->ewmaMs());
        self::assertSame([
            'sourceKey' => 'primary',
            'successRate' => 0.97,
            'ewmaMs' => 123.4,
        ], $signal->toArray());
    }
}
