<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Service\Provider\Location\ProviderQuotaSignalReader;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaDecisionGatewayInterface;
use PHPUnit\Framework\TestCase;

final class ProviderQuotaSignalReaderTest extends TestCase
{
    public function testItBuildsAppOwnedQuotaSignalFromQuotaGateway(): void
    {
        $reader = new ProviderQuotaSignalReader(new class () implements ProviderQuotaDecisionGatewayInterface {
            public function allow(string $tenantId, string $operation, int $units = 1, bool $record = false): bool
            {
                return 'default' === $tenantId && 'reverse' === $operation && 1 === $units && false === $record;
            }
        }, 'default');

        $signal = $reader->read('primary', 'reverse', ['units' => 1]);

        self::assertSame('primary', $signal->sourceKey());
        self::assertSame('reverse', $signal->operation());
        self::assertTrue($signal->allowed());
    }
}
