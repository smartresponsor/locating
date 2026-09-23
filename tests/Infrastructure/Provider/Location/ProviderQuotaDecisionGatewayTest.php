<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Service\Provider\Location\ProviderQuotaDecisionGateway;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaDecisionBackendInterface;
use PHPUnit\Framework\TestCase;

final class ProviderQuotaDecisionGatewayTest extends TestCase
{
    public function testItDelegatesQuotaDecisionToGatewayBackend(): void
    {
        $gateway = new ProviderQuotaDecisionGateway(new class () implements ProviderQuotaDecisionBackendInterface {
            public function allow(string $tenantId, string $resource, int $units = 1, bool $consume = true): bool
            {
                return 'default' === $tenantId && 'suggest' === $resource && 2 === $units && false === $consume;
            }
        });

        self::assertTrue($gateway->allow('default', 'suggest', 2, false));
    }
}
