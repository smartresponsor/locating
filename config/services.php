<?php

declare(strict_types=1);

use App\Locating\Contract\Location\AddressReverseHttpBackendInterface;
use App\Locating\Infrastructure\Provider\Location\AddressReverseGateway;
use App\Locating\Infrastructure\Provider\Location\AddressReverseHttpBackend;
use App\Locating\Infrastructure\Provider\Location\AddressSuggestBackend;
use App\Locating\Infrastructure\Provider\Location\AddressSuggestGateway;
use App\Locating\Infrastructure\Provider\Location\LocationMetricBackend;
use App\Locating\Infrastructure\Provider\Location\ProviderCostCatalogBackend;
use App\Locating\Infrastructure\Provider\Location\ProviderCostCatalogGateway;
use App\Locating\Infrastructure\Provider\Location\ProviderHealthSnapshotBackend;
use App\Locating\Infrastructure\Provider\Location\ProviderHealthSnapshotStore;
use App\Locating\Infrastructure\Provider\Location\ProviderMetricSnapshotBackend;
use App\Locating\Infrastructure\Provider\Location\ProviderMetricSnapshotStore;
use App\Locating\Infrastructure\Provider\Location\ProviderQuotaDecisionBackend;
use App\Locating\Infrastructure\Provider\Location\ProviderQuotaDecisionGateway;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\AddressSuggestBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\LocationMetricBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\MetricSnapshotProviderInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderCostCatalogBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderHealthSnapshotBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderMetricSnapshotBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Backend\ProviderQuotaDecisionBackendInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Gateway\AddressReverseGatewayInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Gateway\AddressSuggestGatewayInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Gateway\ProviderCostCatalogGatewayInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Gateway\ProviderQuotaDecisionGatewayInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Provider\ProviderCostCatalogInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Store\ProviderHealthSnapshotStoreInterface;
use App\Locating\InfrastructureInterface\Provider\Location\Store\ProviderMetricSnapshotStoreInterface;
use App\Locating\Integration\Provider\Location\Http\NominatimReverseHttpClient;
use App\Locating\MessageHandler\Batch\Location\AddressBatchMessageHandler;
use App\Locating\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use App\Locating\Recorder\InMemoryMetricRecorder;
use App\Locating\RecorderInterface\LocationMetricRecorderInterface;
use App\Locating\Service\Address\Location\AddressNormalizer;
use App\Locating\Service\Address\Location\AddressParser;
use App\Locating\Service\Address\Location\AddressPipeline;
use App\Locating\Service\Address\Location\AddressQuotaGuard;
use App\Locating\Service\Address\Location\AddressReverseCapability;
use App\Locating\Service\Address\Location\AddressSuggestCapability;
use App\Locating\Service\Address\Location\AddressValidator;
use App\Locating\Service\Address\Location\LocationResultFactory;
use App\Locating\Service\Batch\Location\AddressBatchJobFactory;
use App\Locating\Service\Batch\Location\AddressBatchJobRecordFactoryBackend;
use App\Locating\Service\Batch\Location\AddressBatchResultBackend;
use App\Locating\Service\Batch\Location\AddressResultFactory;
use App\Locating\Service\Batch\Location\InMemoryAddressBatchMessageBus;
use App\Locating\Service\Batch\Location\InMemoryAddressBatchRuntimeStore;
use App\Locating\Service\Batch\Location\LocationAddressBatchService;
use App\Locating\Service\Batch\Location\LocationAddressBatchServiceMetricDecorator;
use App\Locating\Service\Batch\Location\MessageBusAddressBatchMessageDispatcher;
use App\Locating\Service\Http\Location\LocationAddressReverseHttpService;
use App\Locating\Service\Http\Location\LocationAddressReverseService;
use App\Locating\Service\Http\Location\LocationAddressSuggestHttpService;
use App\Locating\Service\Http\Location\LocationAddressSuggestService;
use App\Locating\Service\Http\Location\LocationGovernanceAcknowledgementHttpService;
use App\Locating\Service\Http\Location\LocationGovernanceAuditHttpService;
use App\Locating\Service\Http\Location\LocationGovernanceExecutionHttpService;
use App\Locating\Service\Http\Location\LocationGovernanceExplanationHttpService;
use App\Locating\Service\Http\Location\LocationGovernanceHttpService;
use App\Locating\Service\Http\Location\LocationGovernanceMetricsHttpService;
use App\Locating\Service\Http\Location\LocationGovernanceRecommendationHttpService;
use App\Locating\Service\Http\Location\LocationGovernanceRemediationPlanHttpService;
use App\Locating\Service\Http\Location\LocationMetricsHttpService;
use App\Locating\Service\Http\Location\LocationQuotaGuard;
use App\Locating\Service\Http\Location\LocationQuotaGuardBackend;
use App\Locating\Service\Http\Location\LocationStatusHttpService;
use App\Locating\Service\Http\Location\LocationViewFactory;
use App\Locating\Service\Location\Tenant\ArrayTenantConfigRepository;
use App\Locating\Service\Location\Tenant\InMemoryTenantUsageCounter;
use App\Locating\Service\Location\Tenant\RequestTenantContext;
use App\Locating\Service\Location\Tenant\TenantQuotaManager;
use App\Locating\Service\Observability\Location\HealthMonitor;
use App\Locating\Service\Observability\Location\LocationMetricsExportService;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceAcknowledgementService;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceAuditService;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceCatalogService;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceExecutionService;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceExplanationService;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceMetricsExportService;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceRecommendationService;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceRemediationPlanService;
use App\Locating\Service\Observability\Location\LocationProviderGovernanceReportService;
use App\Locating\Service\Observability\Location\LocationStatusReportService;
use App\Locating\Service\Provider\Location\AddressReverseProvider;
use App\Locating\Service\Provider\Location\AddressReverseResultNormalizer;
use App\Locating\Service\Provider\Location\AddressSuggestionProvider;
use App\Locating\Service\Provider\Location\AddressSuggestionRanker;
use App\Locating\Service\Provider\Location\CostAwareAddressReverseSourceCostPolicy;
use App\Locating\Service\Provider\Location\CostAwareAddressSuggestionSourceCostPolicy;
use App\Locating\Service\Provider\Location\HealthAwareAddressReverseSourceHealthPolicy;
use App\Locating\Service\Provider\Location\HealthAwareAddressSuggestionSourceHealthPolicy;
use App\Locating\Service\Provider\Location\OrderedAddressReverseProvider;
use App\Locating\Service\Provider\Location\OrderedAddressSuggestionProvider;
use App\Locating\Service\Provider\Location\PolicyAddressReverseSourceOrder;
use App\Locating\Service\Provider\Location\PolicyAddressSuggestionSourceOrder;
use App\Locating\Service\Provider\Location\ProviderCostCatalogService;
use App\Locating\Service\Provider\Location\ProviderCostSignalReader;
use App\Locating\Service\Provider\Location\ProviderHealthSignalReader;
use App\Locating\Service\Provider\Location\ProviderQuotaSignalReader;
use App\Locating\Service\Provider\Location\QuotaAwareAddressReverseSourceQuotaPolicy;
use App\Locating\Service\Provider\Location\QuotaAwareAddressSuggestionSourceQuotaPolicy;
use App\Locating\Service\Provider\Location\StaticAddressReverseSourceOrder;
use App\Locating\Service\Provider\Location\StaticAddressSuggestionSourceOrder;
use App\Locating\ServiceInterface\Address\Location\AddressNormalizerInterface;
use App\Locating\ServiceInterface\Address\Location\AddressParserInterface;
use App\Locating\ServiceInterface\Address\Location\AddressPipelineInterface;
use App\Locating\ServiceInterface\Address\Location\AddressQuotaGuardServiceInterface;
use App\Locating\ServiceInterface\Address\Location\AddressReverseCapabilityInterface;
use App\Locating\ServiceInterface\Address\Location\AddressSuggestCapabilityInterface;
use App\Locating\ServiceInterface\Address\Location\AddressValidatorInterface;
use App\Locating\ServiceInterface\Address\Location\LocationResultFactoryInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobFactoryInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobProgressWriterInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobRecordFactoryBackendInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchJobStoreInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchMessageBusInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchMessageDispatcherInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchResultBackendInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchResultReaderInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchResultWriterInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressResultFactoryInterface;
use App\Locating\ServiceInterface\Batch\Location\LocationAddressBatchServiceInterface;
use App\Locating\ServiceInterface\Http\Location\LocationAddressReverseServiceInterface;
use App\Locating\ServiceInterface\Http\Location\LocationAddressSuggestServiceInterface;
use App\Locating\ServiceInterface\Http\Location\LocationQuotaGuardBackendInterface;
use App\Locating\ServiceInterface\Http\Location\LocationQuotaGuardInterface;
use App\Locating\ServiceInterface\Http\Location\LocationViewFactoryInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantConfigRepositoryInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantContextInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantQuotaManagerInterface;
use App\Locating\ServiceInterface\Location\Tenant\TenantUsageCounterInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationMetricsExportServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceAcknowledgementServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceAuditServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExecutionServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceMetricsExportServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceRecommendationServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceRemediationPlanServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationProviderGovernanceReportServiceInterface;
use App\Locating\ServiceInterface\Observability\Location\LocationStatusReportServiceInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseProviderInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseResultNormalizerInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceCostPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceHealthPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceOrderInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressReverseSourceQuotaPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionProviderInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionRankerInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceCostPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceHealthPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceOrderInterface;
use App\Locating\ServiceInterface\Provider\Location\AddressSuggestionSourceQuotaPolicyInterface;
use App\Locating\ServiceInterface\Provider\Location\Observability\ProviderHealthMonitorInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use App\Locating\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $services->alias(AddressSuggestBackendInterface::class, AddressSuggestBackend::class);
    $services->alias(AddressReverseHttpBackendInterface::class, AddressReverseHttpBackend::class);
    $services->alias(LocationMetricBackendInterface::class, LocationMetricBackend::class);
    $services->alias(MetricSnapshotProviderInterface::class, InMemoryMetricRecorder::class);
    $services->alias(ProviderHealthSnapshotBackendInterface::class, ProviderHealthSnapshotBackend::class);
    $services->alias(ProviderHealthMonitorInterface::class, HealthMonitor::class);
    $services->alias(ProviderMetricSnapshotBackendInterface::class, ProviderMetricSnapshotBackend::class);
    $services->alias(ProviderCostCatalogBackendInterface::class, ProviderCostCatalogBackend::class);
    $services->alias(ProviderCostCatalogInterface::class, ProviderCostCatalogService::class);
    $services->alias(ProviderQuotaDecisionBackendInterface::class, ProviderQuotaDecisionBackend::class);
    $services->alias(TenantQuotaManagerInterface::class, TenantQuotaManager::class);
    $services->alias(AddressSuggestGatewayInterface::class, AddressSuggestGateway::class);
    $services->alias(AddressReverseGatewayInterface::class, AddressReverseGateway::class);
    $services->alias(LocationMetricRecorderInterface::class, InMemoryMetricRecorder::class);
    $services->alias(ProviderHealthSnapshotStoreInterface::class, ProviderHealthSnapshotStore::class);
    $services->alias(ProviderMetricSnapshotStoreInterface::class, ProviderMetricSnapshotStore::class);
    $services->alias(ProviderCostCatalogGatewayInterface::class, ProviderCostCatalogGateway::class);
    $services->alias(ProviderQuotaDecisionGatewayInterface::class, ProviderQuotaDecisionGateway::class);
    $services->alias(AddressSuggestionProviderInterface::class, OrderedAddressSuggestionProvider::class);
    $services->alias(AddressReverseProviderInterface::class, OrderedAddressReverseProvider::class);
    $services->alias(AddressSuggestionRankerInterface::class, AddressSuggestionRanker::class);
    $services->alias(AddressSuggestionSourceHealthPolicyInterface::class, HealthAwareAddressSuggestionSourceHealthPolicy::class);
    $services->alias(ProviderHealthSignalReaderInterface::class, ProviderHealthSignalReader::class);
    $services->alias(ProviderQuotaSignalReaderInterface::class, ProviderQuotaSignalReader::class);
    $services->alias(ProviderCostSignalReaderInterface::class, ProviderCostSignalReader::class);
    $services->alias(AddressSuggestionSourceQuotaPolicyInterface::class, QuotaAwareAddressSuggestionSourceQuotaPolicy::class);
    $services->alias(AddressSuggestionSourceCostPolicyInterface::class, CostAwareAddressSuggestionSourceCostPolicy::class);
    $services->alias(AddressSuggestionSourceOrderInterface::class, PolicyAddressSuggestionSourceOrder::class);
    $services->alias(AddressReverseSourceHealthPolicyInterface::class, HealthAwareAddressReverseSourceHealthPolicy::class);
    $services->alias(AddressReverseSourceQuotaPolicyInterface::class, QuotaAwareAddressReverseSourceQuotaPolicy::class);
    $services->alias(AddressReverseSourceCostPolicyInterface::class, CostAwareAddressReverseSourceCostPolicy::class);
    $services->alias(AddressReverseSourceOrderInterface::class, PolicyAddressReverseSourceOrder::class);
    $services->alias(AddressReverseResultNormalizerInterface::class, AddressReverseResultNormalizer::class);
    $services->alias(AddressSuggestCapabilityInterface::class, AddressSuggestCapability::class);
    $services->alias(AddressReverseCapabilityInterface::class, AddressReverseCapability::class);
    $services->alias(LocationResultFactoryInterface::class, LocationResultFactory::class);
    $services->alias(LocationViewFactoryInterface::class, LocationViewFactory::class);
    $services->alias(LocationQuotaGuardBackendInterface::class, LocationQuotaGuardBackend::class);
    $services->alias(AddressQuotaGuardServiceInterface::class, AddressQuotaGuard::class);
    $services->alias(TenantContextInterface::class, RequestTenantContext::class);
    $services->alias(TenantConfigRepositoryInterface::class, ArrayTenantConfigRepository::class);
    $services->alias(TenantUsageCounterInterface::class, InMemoryTenantUsageCounter::class);
    $services->alias(LocationQuotaGuardInterface::class, LocationQuotaGuard::class);
    $services->alias(LocationAddressSuggestServiceInterface::class, LocationAddressSuggestService::class);
    $services->alias(LocationAddressReverseServiceInterface::class, LocationAddressReverseService::class);
    $services->alias(LocationStatusReportServiceInterface::class, LocationStatusReportService::class);
    $services->alias(LocationMetricsExportServiceInterface::class, LocationMetricsExportService::class);
    $services->alias(LocationProviderGovernanceCatalogServiceInterface::class, LocationProviderGovernanceCatalogService::class);
    $services->alias(LocationProviderGovernanceReportServiceInterface::class, LocationProviderGovernanceReportService::class);
    $services->alias(LocationProviderGovernanceMetricsExportServiceInterface::class, LocationProviderGovernanceMetricsExportService::class);
    $services->alias(LocationProviderGovernanceExplanationServiceInterface::class, LocationProviderGovernanceExplanationService::class);
    $services->alias(LocationProviderGovernanceRecommendationServiceInterface::class, LocationProviderGovernanceRecommendationService::class);
    $services->alias(LocationProviderGovernanceAuditServiceInterface::class, LocationProviderGovernanceAuditService::class);
    $services->alias(LocationProviderGovernanceRemediationPlanServiceInterface::class, LocationProviderGovernanceRemediationPlanService::class);
    $services->alias(LocationProviderGovernanceExecutionServiceInterface::class, LocationProviderGovernanceExecutionService::class);
    $services->alias(LocationProviderGovernanceAcknowledgementServiceInterface::class, LocationProviderGovernanceAcknowledgementService::class);
    $services->alias(AddressParserInterface::class, AddressParser::class);
    $services->alias(AddressNormalizerInterface::class, AddressNormalizer::class);
    $services->alias(AddressValidatorInterface::class, AddressValidator::class);
    $services->alias(AddressPipelineInterface::class, AddressPipeline::class);
    $services->alias(AddressResultFactoryInterface::class, AddressResultFactory::class);
    $services->alias(AddressBatchJobFactoryInterface::class, AddressBatchJobFactory::class);
    $services->alias(AddressBatchJobStoreInterface::class, InMemoryAddressBatchRuntimeStore::class);
    $services->alias(AddressBatchMessageBusInterface::class, InMemoryAddressBatchMessageBus::class);
    $services->alias(AddressBatchMessageDispatcherInterface::class, MessageBusAddressBatchMessageDispatcher::class);
    $services->alias(AddressBatchResultReaderInterface::class, InMemoryAddressBatchRuntimeStore::class);
    $services->alias(AddressBatchJobProgressWriterInterface::class, InMemoryAddressBatchRuntimeStore::class);
    $services->alias(AddressBatchResultWriterInterface::class, InMemoryAddressBatchRuntimeStore::class);
    $services->alias(LocationAddressBatchServiceInterface::class, LocationAddressBatchServiceMetricDecorator::class);
    $services->alias(AddressBatchJobRecordFactoryBackendInterface::class, AddressBatchJobRecordFactoryBackend::class);
    $services->alias(AddressBatchResultBackendInterface::class, AddressBatchResultBackend::class);

    $services->set(AddressSuggestBackend::class)
        ->args([[]]);

    $services->set(AddressSuggestGateway::class)
        ->args([
            service(AddressSuggestBackendInterface::class),
        ]);

    $services->set(NominatimReverseHttpClient::class);

    $services->set(AddressReverseHttpBackend::class)
        ->args([
            service(NominatimReverseHttpClient::class),
        ]);

    $services->set(AddressReverseGateway::class)
        ->args([
            service(AddressReverseHttpBackendInterface::class),
        ]);

    $services->set(LocationMetricBackend::class);

    $services->set(InMemoryMetricRecorder::class);

    $services->set(HealthMonitor::class);
    $services->set(ProviderHealthSnapshotBackend::class);

    $services->set(ProviderHealthSnapshotStore::class)
        ->args([
            service(ProviderHealthSnapshotBackendInterface::class),
        ]);

    $services->set(ProviderMetricSnapshotBackend::class);

    $services->set(ProviderMetricSnapshotStore::class)
        ->args([
            service(ProviderMetricSnapshotBackendInterface::class),
        ]);

    $services->set(ProviderCostCatalogService::class);
    $services->set(ProviderCostCatalogBackend::class);

    $services->set(ProviderCostCatalogGateway::class)
        ->args([
            service(ProviderCostCatalogBackendInterface::class),
        ]);

    $services->set(TenantQuotaManager::class);
    $services->set(ProviderQuotaDecisionBackend::class);

    $services->set(ProviderQuotaDecisionGateway::class)
        ->args([
            service(ProviderQuotaDecisionBackendInterface::class),
        ]);

    $services->set(AddressSuggestionProvider::class)
        ->args([
            service(AddressSuggestGatewayInterface::class),
            service(LocationResultFactoryInterface::class),
        ]);

    $services->set(AddressSuggestionRanker::class);

    $services->set(ProviderHealthSignalReader::class)
        ->args([
            service(ProviderHealthSnapshotStoreInterface::class),
        ]);

    $services->set(ProviderQuotaSignalReader::class)
        ->args([
            service(ProviderQuotaDecisionGatewayInterface::class),
            'default',
        ]);

    $services->set(ProviderCostSignalReader::class)
        ->args([
            service(ProviderCostCatalogGatewayInterface::class),
            'global',
        ]);

    $services->set(HealthAwareAddressSuggestionSourceHealthPolicy::class)
        ->args([
            service(ProviderHealthSignalReaderInterface::class),
        ]);

    $services->set(QuotaAwareAddressSuggestionSourceQuotaPolicy::class)
        ->args([
            service(ProviderQuotaSignalReaderInterface::class),
        ]);

    $services->set(CostAwareAddressSuggestionSourceCostPolicy::class)
        ->args([
            service(ProviderCostSignalReaderInterface::class),
        ]);

    $services->set(PolicyAddressSuggestionSourceOrder::class)
        ->args([
            service(AddressSuggestionSourceHealthPolicyInterface::class),
            service(AddressSuggestionSourceQuotaPolicyInterface::class),
            service(AddressSuggestionSourceCostPolicyInterface::class),
        ]);

    $services->set(StaticAddressSuggestionSourceOrder::class);

    $services->set(OrderedAddressSuggestionProvider::class)
        ->args([
            [service(AddressSuggestionProvider::class)],
            service(AddressSuggestionSourceOrderInterface::class),
            service(AddressSuggestionRankerInterface::class),
        ]);

    $services->set(AddressReverseProvider::class)
        ->args([
            service(AddressReverseGatewayInterface::class),
            service(LocationResultFactoryInterface::class),
            service(LocationMetricRecorderInterface::class),
        ]);

    $services->set(AddressReverseResultNormalizer::class);

    $services->set(HealthAwareAddressReverseSourceHealthPolicy::class)
        ->args([
            service(ProviderHealthSignalReaderInterface::class),
        ]);

    $services->set(QuotaAwareAddressReverseSourceQuotaPolicy::class)
        ->args([
            service(ProviderQuotaSignalReaderInterface::class),
        ]);

    $services->set(CostAwareAddressReverseSourceCostPolicy::class)
        ->args([
            service(ProviderCostSignalReaderInterface::class),
        ]);

    $services->set(PolicyAddressReverseSourceOrder::class)
        ->args([
            service(AddressReverseSourceHealthPolicyInterface::class),
            service(AddressReverseSourceQuotaPolicyInterface::class),
            service(AddressReverseSourceCostPolicyInterface::class),
        ]);

    $services->set(StaticAddressReverseSourceOrder::class);

    $services->set(OrderedAddressReverseProvider::class)
        ->args([
            [service(AddressReverseProvider::class)],
            service(AddressReverseSourceOrderInterface::class),
            service(AddressReverseResultNormalizerInterface::class),
        ]);

    $services->set(AddressSuggestCapability::class)
        ->args([
            service(AddressSuggestionProviderInterface::class),
        ]);

    $services->set(AddressReverseCapability::class)
        ->args([
            service(AddressReverseProviderInterface::class),
        ]);

    $services->set(LocationResultFactory::class);
    $services->set(LocationViewFactory::class);
    $services->set(RequestTenantContext::class);
    $services->set(ArrayTenantConfigRepository::class);
    $services->set(InMemoryTenantUsageCounter::class);
    $services->set(AddressQuotaGuard::class);
    $services->set(LocationQuotaGuardBackend::class);
    $services->set(LocationQuotaGuard::class);
    $services->set(AddressParser::class);
    $services->set(AddressNormalizer::class);
    $services->set(AddressValidator::class);

    $services->set(AddressPipeline::class)
        ->args([
            service(AddressParserInterface::class),
            service(AddressNormalizerInterface::class),
            service(AddressValidatorInterface::class),
        ]);

    $services->set(AddressResultFactory::class)
        ->args([
            service(AddressBatchResultBackendInterface::class),
        ]);

    $services->set(AddressBatchJobFactory::class);

    $services->set(MessageBusAddressBatchMessageDispatcher::class)
        ->args([
            service(AddressBatchMessageBusInterface::class),
        ]);

    $services->set(LocationAddressBatchService::class)
        ->args([
            service(AddressBatchJobStoreInterface::class),
            service(AddressBatchMessageDispatcherInterface::class),
            service(AddressBatchResultReaderInterface::class),
        ]);

    $services->set(LocationAddressBatchServiceMetricDecorator::class)
        ->args([
            service(LocationAddressBatchService::class),
            service(LocationMetricRecorderInterface::class),
        ]);

    $services->alias(AddressBatchMessageHandlerInterface::class, AddressBatchMessageHandler::class);

    $services->set(LocationGovernanceHttpService::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceReportServiceInterface::class),
        ]);

    $services->set(LocationGovernanceMetricsHttpService::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceMetricsExportServiceInterface::class),
        ]);

    $services->set(AddressBatchMessageHandler::class)
        ->args([
            service(AddressPipelineInterface::class),
            service(AddressBatchJobProgressWriterInterface::class),
            service(AddressBatchResultWriterInterface::class),
        ]);

    $services->set(LocationAddressSuggestService::class)
        ->args([
            service(AddressSuggestCapabilityInterface::class),
            service(LocationViewFactoryInterface::class),
        ]);

    $services->set(LocationAddressReverseService::class)
        ->args([
            service(AddressReverseCapabilityInterface::class),
            service(LocationViewFactoryInterface::class),
        ]);

    $services->set(LocationProviderGovernanceCatalogService::class)
        ->args([
            service(ProviderHealthSignalReaderInterface::class),
            service(ProviderQuotaSignalReaderInterface::class),
            service(ProviderCostSignalReaderInterface::class),
            [
                'suggest' => 'suggest',
                'reverse' => 'reverse',
            ],
        ]);

    $services->set(LocationProviderGovernanceReportService::class)
        ->args([
            service(LocationProviderGovernanceCatalogServiceInterface::class),
        ]);

    $services->set(LocationProviderGovernanceMetricsExportService::class)
        ->args([
            service(LocationProviderGovernanceCatalogServiceInterface::class),
        ]);

    $services->set(LocationProviderGovernanceExplanationService::class)
        ->args([
            service(LocationProviderGovernanceCatalogServiceInterface::class),
        ]);

    $services->set(LocationProviderGovernanceRecommendationService::class)
        ->args([
            service(LocationProviderGovernanceExplanationServiceInterface::class),
        ]);

    $services->set(LocationProviderGovernanceAuditService::class)
        ->args([
            service(LocationProviderGovernanceExplanationServiceInterface::class),
            service(LocationProviderGovernanceRecommendationServiceInterface::class),
        ]);

    $services->set(LocationProviderGovernanceRemediationPlanService::class)
        ->args([
            service(LocationProviderGovernanceAuditServiceInterface::class),
        ]);

    $services->set(LocationProviderGovernanceExecutionService::class)
        ->args([
            service(LocationProviderGovernanceRemediationPlanServiceInterface::class),
        ]);

    $services->set(LocationProviderGovernanceAcknowledgementService::class)
        ->args([
            service(LocationProviderGovernanceExecutionServiceInterface::class),
        ]);

    $services->set(LocationStatusReportService::class)
        ->args([
            service(ProviderMetricSnapshotStoreInterface::class),
            service(LocationProviderGovernanceCatalogServiceInterface::class),
        ]);

    $services->set(LocationMetricsExportService::class)
        ->args([
            service(ProviderMetricSnapshotStoreInterface::class),
            service(LocationProviderGovernanceCatalogServiceInterface::class),
        ]);

    $services->set(LocationAddressSuggestHttpService::class)
        ->public()
        ->args([
            service(LocationAddressSuggestServiceInterface::class),
            service(LocationQuotaGuardInterface::class),
        ]);

    $services->set(LocationAddressReverseHttpService::class)
        ->public()
        ->args([
            service(LocationAddressReverseServiceInterface::class),
            service(LocationQuotaGuardInterface::class),
        ]);

    $services->set(LocationStatusHttpService::class)
        ->public()
        ->args([
            service(LocationStatusReportServiceInterface::class),
        ]);

    $services->set(LocationMetricsHttpService::class)
        ->public()
        ->args([
            service(LocationMetricsExportServiceInterface::class),
        ]);

    $services->set(LocationGovernanceExplanationHttpService::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceExplanationServiceInterface::class),
        ]);

    $services->set(LocationGovernanceRecommendationHttpService::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceRecommendationServiceInterface::class),
        ]);

    $services->set(LocationGovernanceAuditHttpService::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceAuditServiceInterface::class),
        ]);

    $services->set(LocationGovernanceRemediationPlanHttpService::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceRemediationPlanServiceInterface::class),
        ]);

    $services->set(LocationGovernanceExecutionHttpService::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceExecutionServiceInterface::class),
        ]);

    $services->set(LocationGovernanceAcknowledgementHttpService::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceAcknowledgementServiceInterface::class),
        ]);
};
