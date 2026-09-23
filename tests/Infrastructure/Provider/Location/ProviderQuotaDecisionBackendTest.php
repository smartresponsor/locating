<?php

declare(strict_types=1);

namespace App\Locating\Tests\Infrastructure\Provider\Location;

use App\Locating\Service\Provider\Location\ProviderQuotaDecisionBackend;
use App\Locating\ServiceInterface\Location\Tenant\TenantQuotaManagerInterface;
use PHPUnit\Framework\TestCase;

final class ProviderQuotaDecisionBackendTest extends TestCase
{
    public function testAllowDelegatesToQuotaManager(): void
    {
        $backend = new ProviderQuotaDecisionBackend(new class () implements TenantQuotaManagerInterface {
            public function allow(string $tenantId, string $op, int $unit = 1, bool $consume = true): bool
            {
                return 't' === $tenantId && !$consume;
            }

            public function remaining(string $tenantId, string $op): int
            {
                return 7;
            }
        });
        self::assertTrue($backend->allow('t', 'suggest', 1, false));
    }
}
