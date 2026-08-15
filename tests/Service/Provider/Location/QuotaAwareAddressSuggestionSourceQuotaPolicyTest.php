<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\ReadModel\Observability\Location\ProviderQuotaSignal;
use App\Locating\Service\Provider\Location\QuotaAwareAddressSuggestionSourceQuotaPolicy;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class QuotaAwareAddressSuggestionSourceQuotaPolicyTest extends TestCase
{
    public function testAllowsDelegatesToAppOwnedQuotaSignalReader(): void
    {
        $seen = [];
        $policy = new QuotaAwareAddressSuggestionSourceQuotaPolicy(
            new class ($seen) implements ProviderQuotaSignalReaderInterface {
                public function __construct(private array &$seen)
                {
                }

                public function read(string $sourceKey, string $operation, array $context = []): \App\Locating\ReadModelInterface\Observability\Location\ProviderQuotaSignalInterface
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
