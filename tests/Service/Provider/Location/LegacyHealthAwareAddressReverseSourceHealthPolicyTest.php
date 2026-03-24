<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\Entity\Location\ProviderHealthSignal;
use App\Service\Provider\Location\LegacyHealthAwareAddressReverseSourceHealthPolicy;
use App\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class LegacyHealthAwareAddressReverseSourceHealthPolicyTest extends TestCase
{
    public function testScoreUsesAppOwnedHealthSignal(): void
    {
        $policy = new LegacyHealthAwareAddressReverseSourceHealthPolicy(
            new class implements ProviderHealthSignalReaderInterface {
                public function read(string $sourceKey): \App\EntityInterface\Location\ProviderHealthSignalInterface
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
