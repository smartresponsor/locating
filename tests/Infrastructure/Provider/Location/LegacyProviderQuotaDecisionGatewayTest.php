<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\ProviderQuotaDecisionLegacyManagerInterface;
use App\Infrastructure\Provider\Location\LegacyProviderQuotaDecisionGateway;
use PHPUnit\Framework\TestCase;

final class LegacyProviderQuotaDecisionGatewayTest extends TestCase
{
    public function testItDelegatesQuotaDecisionToLegacyGateway(): void
    {
        $gateway = new LegacyProviderQuotaDecisionGateway(new class implements ProviderQuotaDecisionLegacyManagerInterface {
            public function allow(string $tenantId, string $resource, int $units = 1, bool $consume = true): bool
            {
                return 'default' === $tenantId && 'suggest' === $resource && 2 === $units && false === $consume;
            }
        });

        self::assertTrue($gateway->allow('default', 'suggest', 2, false));
    }
}
