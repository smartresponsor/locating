<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\Entity\Location\ProviderHealthSignal;
use App\Service\Provider\Location\LegacyHealthAwareAddressSuggestionSourceHealthPolicy;
use App\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class LegacyHealthAwareAddressSuggestionSourceHealthPolicyTest extends TestCase
{
    public function testScoreUsesAppOwnedHealthSignal(): void
    {
        $policy = new LegacyHealthAwareAddressSuggestionSourceHealthPolicy(
            new class implements ProviderHealthSignalReaderInterface {
                public function read(string $sourceKey): \App\EntityInterface\Location\ProviderHealthSignalInterface
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
