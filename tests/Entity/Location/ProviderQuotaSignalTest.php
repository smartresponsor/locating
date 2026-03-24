<?php

declare(strict_types=1);

namespace Tests\Entity\Location;

use App\Entity\Location\ProviderQuotaSignal;
use PHPUnit\Framework\TestCase;

final class ProviderQuotaSignalTest extends TestCase
{
    public function testItExposesTypedValues(): void
    {
        $signal = new ProviderQuotaSignal('primary', 'geocode', true);

        self::assertSame('primary', $signal->sourceKey());
        self::assertSame('geocode', $signal->operation());
        self::assertTrue($signal->allowed());
        self::assertSame([
            'sourceKey' => 'primary',
            'operation' => 'geocode',
            'allowed' => true,
        ], $signal->toArray());
    }
}
