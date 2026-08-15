<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\ReadModel\Observability\Location\ProviderHealthSignal;
use App\Locating\Service\Provider\Location\HealthAwareAddressReverseSourceHealthPolicy;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class HealthAwareAddressReverseSourceHealthPolicyTest extends TestCase
{
    public function testScoreUsesAppOwnedHealthSignal(): void
    {
        $policy = new HealthAwareAddressReverseSourceHealthPolicy(
            new class () implements ProviderHealthSignalReaderInterface {
                public function read(string $sourceKey): \App\Locating\ReadModelInterface\Observability\Location\ProviderHealthSignalInterface
                {
                    return match ($sourceKey) {
                        'legacy-reverse' => new ProviderHealthSignal('legacy-reverse', 0.9, 200.0),
                        default => new ProviderHealthSignal($sourceKey, 0.5, 500.0),
                    };
                }
            }
        );

        self::assertGreaterThan(0.5, $policy->score('legacy-reverse'));
        self::assertSame(0.5, $policy->score('missing-source'));
    }
}
