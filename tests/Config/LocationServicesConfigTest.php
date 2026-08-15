<?php

declare(strict_types=1);

namespace App\Locating\Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationServicesConfigTest extends TestCase
{
    public function testServicesConfigWiresCanonicalAppAliases(): void
    {
        $content = file_get_contents(__DIR__.'/../../config/services.php');
        self::assertIsString($content);

        self::assertStringContainsString('LocationAddressSuggestServiceInterface::class, LocationAddressSuggestService::class', $content);
        self::assertStringContainsString('LocationAddressReverseServiceInterface::class, LocationAddressReverseService::class', $content);
        self::assertStringContainsString('AddressSuggestCapabilityInterface::class, AddressSuggestCapability::class', $content);
        self::assertStringContainsString('AddressReverseCapabilityInterface::class, AddressReverseCapability::class', $content);
        self::assertStringContainsString('AddressSuggestionProviderInterface::class, OrderedAddressSuggestionProvider::class', $content);
        self::assertStringContainsString('AddressSuggestionRankerInterface::class, AddressSuggestionRanker::class', $content);
        self::assertStringContainsString('AddressSuggestionSourceHealthPolicyInterface::class, HealthAwareAddressSuggestionSourceHealthPolicy::class', $content);
        self::assertStringContainsString('ProviderHealthSnapshotStoreInterface::class, ProviderHealthSnapshotStore::class', $content);
        self::assertStringContainsString('ProviderQuotaDecisionGatewayInterface::class, ProviderQuotaDecisionGateway::class', $content);
        self::assertStringContainsString('ProviderCostCatalogGatewayInterface::class, ProviderCostCatalogGateway::class', $content);
        self::assertStringContainsString('ProviderHealthSignalReaderInterface::class, ProviderHealthSignalReader::class', $content);
        self::assertStringContainsString('ProviderQuotaSignalReaderInterface::class, ProviderQuotaSignalReader::class', $content);
        self::assertStringContainsString('ProviderCostSignalReaderInterface::class, ProviderCostSignalReader::class', $content);
        self::assertStringContainsString('AddressSuggestionSourceQuotaPolicyInterface::class, QuotaAwareAddressSuggestionSourceQuotaPolicy::class', $content);
        self::assertStringContainsString('AddressSuggestionSourceCostPolicyInterface::class, CostAwareAddressSuggestionSourceCostPolicy::class', $content);
        self::assertStringContainsString('AddressSuggestionSourceOrderInterface::class, PolicyAddressSuggestionSourceOrder::class', $content);
        self::assertStringContainsString('AddressReverseProviderInterface::class, OrderedAddressReverseProvider::class', $content);
        self::assertStringContainsString('AddressReverseSourceHealthPolicyInterface::class, HealthAwareAddressReverseSourceHealthPolicy::class', $content);
        self::assertStringContainsString('AddressReverseSourceQuotaPolicyInterface::class, QuotaAwareAddressReverseSourceQuotaPolicy::class', $content);
        self::assertStringContainsString('AddressReverseSourceCostPolicyInterface::class, CostAwareAddressReverseSourceCostPolicy::class', $content);
        self::assertStringContainsString('AddressReverseSourceOrderInterface::class, PolicyAddressReverseSourceOrder::class', $content);
        self::assertStringContainsString('AddressReverseResultNormalizerInterface::class, AddressReverseResultNormalizer::class', $content);
    }

    public function testServicesConfigPublishesCanonicalHttpLocationServices(): void
    {
        $content = file_get_contents(__DIR__.'/../../config/services.php');
        self::assertIsString($content);

        self::assertStringContainsString('set(LocationAddressSuggestHttpService::class)', $content);
        self::assertStringContainsString('set(LocationAddressReverseHttpService::class)', $content);
        self::assertStringContainsString('set(LocationStatusHttpService::class)', $content);
        self::assertStringContainsString('set(LocationMetricsHttpService::class)', $content);
        self::assertStringNotContainsString('App\Locating\\Service\\Locator\\', $content);
    }
}
