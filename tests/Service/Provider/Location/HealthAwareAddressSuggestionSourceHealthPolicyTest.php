<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\Policy\Provider\Location\HealthAwareAddressSuggestionSourceHealthPolicy;
use App\Locating\ReadModel\Observability\Location\ProviderHealthSignal;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class HealthAwareAddressSuggestionSourceHealthPolicyTest extends TestCase
{
    public function testScoreUsesAppOwnedHealthSignal(): void
    {
        $policy = new HealthAwareAddressSuggestionSourceHealthPolicy(
            new class () implements ProviderHealthSignalReaderInterface {
                public function read(string $sourceKey): \App\Locating\ReadModelInterface\Observability\Location\ProviderHealthSignalInterface
                {
                    return match ($sourceKey) {
                        'legacy-suggest' => new ProviderHealthSignal('legacy-suggest', 0.95, 120.0),
                        default => new ProviderHealthSignal($sourceKey, 0.5, 500.0),
                    };
                }
            }
        );

        self::assertGreaterThan(0.8, $policy->score('legacy-suggest'));
        self::assertSame(0.5, $policy->score('unknown'));
    }
}
