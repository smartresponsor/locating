<?php

declare(strict_types=1);

namespace Tests\Config;

use PHPUnit\Framework\TestCase;

final class LocationServicesConfigTest extends TestCase
{
    public function testServicesConfigWiresCanonicalAppAliases(): void
    {
        $content = file_get_contents(__DIR__.'/../../config/services.php');
        self::assertIsString($content);

        self::assertStringContainsString('AddressSuggestServiceInterface::class, AddressSuggestService::class', $content);
        self::assertStringContainsString('AddressReverseServiceInterface::class, AddressReverseService::class', $content);
        self::assertStringContainsString('AddressSuggestCapabilityInterface::class, AddressSuggestCapability::class', $content);
        self::assertStringContainsString('AddressReverseCapabilityInterface::class, AddressReverseCapability::class', $content);
        self::assertStringContainsString('AddressSuggestionProviderInterface::class, OrderedAddressSuggestionProvider::class', $content);
        self::assertStringContainsString('AddressSuggestionRankerInterface::class, AddressSuggestionRanker::class', $content);
        self::assertStringContainsString('AddressSuggestionSourceHealthPolicyInterface::class, LegacyHealthAwareAddressSuggestionSourceHealthPolicy::class', $content);
        self::assertStringContainsString('ProviderHealthSnapshotStoreInterface::class, LegacyProviderHealthSnapshotStore::class', $content);
        self::assertStringContainsString('ProviderQuotaDecisionGatewayInterface::class, LegacyProviderQuotaDecisionGateway::class', $content);
        self::assertStringContainsString('ProviderCostCatalogGatewayInterface::class, LegacyProviderCostCatalogGateway::class', $content);
        self::assertStringContainsString('ProviderHealthSignalReaderInterface::class, LegacyProviderHealthSignalReader::class', $content);
        self::assertStringContainsString('ProviderQuotaSignalReaderInterface::class, LegacyProviderQuotaSignalReader::class', $content);
        self::assertStringContainsString('ProviderCostSignalReaderInterface::class, LegacyProviderCostSignalReader::class', $content);
        self::assertStringContainsString('AddressSuggestionSourceQuotaPolicyInterface::class, LegacyQuotaAwareAddressSuggestionSourceQuotaPolicy::class', $content);
        self::assertStringContainsString('AddressSuggestionSourceCostPolicyInterface::class, LegacyCostAwareAddressSuggestionSourceCostPolicy::class', $content);
        self::assertStringContainsString('AddressSuggestionSourceOrderInterface::class, PolicyAddressSuggestionSourceOrder::class', $content);
        self::assertStringContainsString('AddressReverseProviderInterface::class, OrderedAddressReverseProvider::class', $content);
        self::assertStringContainsString('AddressReverseSourceHealthPolicyInterface::class, LegacyHealthAwareAddressReverseSourceHealthPolicy::class', $content);
        self::assertStringContainsString('AddressReverseSourceQuotaPolicyInterface::class, LegacyQuotaAwareAddressReverseSourceQuotaPolicy::class', $content);
        self::assertStringContainsString('AddressReverseSourceCostPolicyInterface::class, LegacyCostAwareAddressReverseSourceCostPolicy::class', $content);
        self::assertStringContainsString('AddressReverseSourceOrderInterface::class, PolicyAddressReverseSourceOrder::class', $content);
        self::assertStringContainsString('AddressReverseResultNormalizerInterface::class, AddressReverseResultNormalizer::class', $content);
    }

    public function testServicesConfigPublishesCanonicalHttpLocationControllers(): void
    {
        $content = file_get_contents(__DIR__.'/../../config/services.php');
        self::assertIsString($content);

        self::assertStringContainsString('set(AddressSuggestController::class)', $content);
        self::assertStringContainsString('set(AddressReverseController::class)', $content);
        self::assertStringContainsString('set(StatusController::class)', $content);
        self::assertStringContainsString('set(MetricsController::class)', $content);
        self::assertStringNotContainsString('App\\Controller\\Locator\\', $content);
    }
}
