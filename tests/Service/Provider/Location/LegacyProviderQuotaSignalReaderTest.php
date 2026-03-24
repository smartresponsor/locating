<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\InfrastructureInterface\Provider\Location\ProviderQuotaDecisionGatewayInterface;
use App\Service\Provider\Location\LegacyProviderQuotaSignalReader;
use PHPUnit\Framework\TestCase;

final class LegacyProviderQuotaSignalReaderTest extends TestCase
{
    public function testItBuildsAppOwnedQuotaSignalFromQuotaGateway(): void
    {
        $reader = new LegacyProviderQuotaSignalReader(new class implements ProviderQuotaDecisionGatewayInterface {
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
