<?php

declare(strict_types=1);

namespace Tests\Service\Http\Location;

use App\Service\Http\Location\LocationQuotaGuard;
use App\ServiceInterface\Http\Location\LocationQuotaGuardBackendInterface;
use App\ServiceInterface\Http\Location\LocationQuotaGuardInterface;
use PHPUnit\Framework\TestCase;

final class LocationQuotaGuardTest extends TestCase
{
    public function testMapsSuggestAndReverseOperationsToBackendMethods(): void
    {
        $backend = new class implements LocationQuotaGuardBackendInterface {
            public bool $suggestCalled = false;
            public bool $reverseCalled = false;

            public function isAllowedSuggest(): bool
            {
                $this->suggestCalled = true;

                return true;
            }

            public function isAllowedReverse(): bool
            {
                $this->reverseCalled = true;

                return false;
            }
        };

        $guard = new LocationQuotaGuard($backend);

        self::assertTrue($guard->isAllowed(LocationQuotaGuardInterface::OPERATION_SUGGEST));
        self::assertFalse($guard->isAllowed(LocationQuotaGuardInterface::OPERATION_REVERSE));
        self::assertTrue($backend->suggestCalled);
        self::assertTrue($backend->reverseCalled);
        self::assertFalse($guard->isAllowed('unknown'));
    }
}
