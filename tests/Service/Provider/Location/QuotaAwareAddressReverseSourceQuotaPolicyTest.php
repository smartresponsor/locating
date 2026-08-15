<?php

declare(strict_types=1);

namespace App\Locating\Tests\Service\Provider\Location;

use App\Locating\ReadModel\Observability\Location\ProviderQuotaSignal;
use App\Locating\Service\Provider\Location\QuotaAwareAddressReverseSourceQuotaPolicy;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;
use PHPUnit\Framework\TestCase;

final class QuotaAwareAddressReverseSourceQuotaPolicyTest extends TestCase
{
    public function testAllowsDelegatesToAppOwnedQuotaSignalReader(): void
    {
        $seen = [];
        $policy = new QuotaAwareAddressReverseSourceQuotaPolicy(
            new class ($seen) implements ProviderQuotaSignalReaderInterface {
                public function __construct(private array &$seen)
                {
                }

                public function read(string $sourceKey, string $operation, array $context = []): \App\Locating\ReadModelInterface\Observability\Location\ProviderQuotaSignalInterface
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
