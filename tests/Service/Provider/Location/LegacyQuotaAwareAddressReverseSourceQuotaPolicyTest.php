<?php

declare(strict_types=1);

namespace Tests\Service\Provider\Location;

use App\Entity\Location\ProviderQuotaSignal;
use App\Service\Provider\Location\LegacyQuotaAwareAddressReverseSourceQuotaPolicy;
use App\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class LegacyQuotaAwareAddressReverseSourceQuotaPolicyTest extends TestCase
{
    public function testAllowsDelegatesToAppOwnedQuotaSignalReader(): void
    {
        $seen = [];
        $policy = new LegacyQuotaAwareAddressReverseSourceQuotaPolicy(
            new class($seen) implements ProviderQuotaSignalReaderInterface {
                public function __construct(private array &$seen)
                {
                }

                public function read(string $sourceKey, string $operation, array $context = []): \App\EntityInterface\Location\ProviderQuotaSignalInterface
                {
                    $this->seen = [$sourceKey, $operation, $context['latitude'] ?? null, $context['longitude'] ?? null];

                    return new ProviderQuotaSignal($sourceKey, $operation, true);
                }
            }
        );

        self::assertTrue($policy->allows('legacy-reverse', 29.7604, -95.3698, 'US'));
        self::assertSame(['legacy-reverse', 'reverse', 29.7604, -95.3698], $seen);
    }
}
