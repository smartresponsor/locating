<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\Entity\Location\ProviderQuotaSignal;
use App\Service\Provider\Location\LegacyQuotaAwareAddressSuggestionSourceQuotaPolicy;
use App\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class LegacyQuotaAwareAddressSuggestionSourceQuotaPolicyTest extends TestCase
{
    public function testAllowsDelegatesToAppOwnedQuotaSignalReader(): void
    {
        $seen = [];
        $policy = new LegacyQuotaAwareAddressSuggestionSourceQuotaPolicy(
            new class($seen) implements ProviderQuotaSignalReaderInterface {
                public function __construct(private array &$seen)
                {
                }

                public function read(string $sourceKey, string $operation, array $context = []): \App\EntityInterface\Location\ProviderQuotaSignalInterface
                {
                    $this->seen = [$sourceKey, $operation, $context['limit'] ?? null, $context['countryCode'] ?? null];

                    return new ProviderQuotaSignal($sourceKey, $operation, true);
                }
            }
        );

        self::assertTrue($policy->allows('legacy-suggest', 'main', 'US', 5));
        self::assertSame(['legacy-suggest', 'geocode', 5, 'US'], $seen);
        self::assertFalse($policy->allows('legacy-suggest', '', 'US', 5));
    }
}
