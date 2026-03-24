<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Provider\Location;

use App\Bridge\Legacy\Provider\Location\ProviderQuotaDecisionLegacyManagerInterface;
use App\Infrastructure\Provider\Location\SmartresponsorProviderQuotaDecisionBackend;
use PHPUnit\Framework\TestCase;

final class SmartresponsorProviderQuotaDecisionBackendTest extends TestCase
{
    public function testAllowDelegatesToLegacyQuotaManager(): void
    {
        $backend = new SmartresponsorProviderQuotaDecisionBackend(new class implements ProviderQuotaDecisionLegacyManagerInterface {
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
