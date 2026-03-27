<?php

declare(strict_types=1);

use App\Bridge\Legacy\Batch\Location\AddressBatchLegacyMessageHandler;
use App\Controller\Http\Location\AddressReverseController;
use App\Controller\Http\Location\AddressSuggestController;
use App\Controller\Http\Location\GovernanceAcknowledgementController;
use App\Controller\Http\Location\GovernanceAuditController;
use App\Controller\Http\Location\GovernanceController;
use App\Controller\Http\Location\GovernanceExecutionController;
use App\Controller\Http\Location\GovernanceExplanationController;
use App\Controller\Http\Location\GovernanceMetricsController;
use App\Controller\Http\Location\GovernanceRecommendationController;
use App\Controller\Http\Location\GovernanceRemediationPlanController;
use App\Controller\Http\Location\MetricsController;
use App\Controller\Http\Location\StatusController;
use App\Infrastructure\Batch\Location\LegacyAddressBatchJobProgressWriter;
use App\Infrastructure\Batch\Location\LegacyAddressBatchJobStore;
use App\Infrastructure\Batch\Location\LegacyAddressBatchMessageBus;
use App\Infrastructure\Batch\Location\LegacyAddressBatchResultReader;
use App\Infrastructure\Batch\Location\LegacyAddressBatchResultWriter;
use App\Infrastructure\Batch\Location\MessageBusAddressBatchMessageDispatcher;
use App\Infrastructure\Batch\Location\SmartresponsorAddressBatchJobRecordFactoryBackend;
use App\Infrastructure\Batch\Location\SmartresponsorAddressBatchJobRepositoryBackend;
use App\Infrastructure\Batch\Location\SmartresponsorAddressBatchLegacyMessageBusBackend;
use App\Infrastructure\Batch\Location\SmartresponsorAddressBatchLegacyResultBackend;
use App\Infrastructure\Batch\Location\SmartresponsorAddressBatchResultStorageBackend;
use App\Infrastructure\Provider\Location\LegacyAddressReverseGateway;
use App\Infrastructure\Provider\Location\LegacyAddressSuggestGateway;
use App\Infrastructure\Provider\Location\LegacyLocationMetricRecorder;
use App\Infrastructure\Provider\Location\LegacyProviderCostCatalogGateway;
use App\Infrastructure\Provider\Location\LegacyProviderHealthSnapshotStore;
use App\Infrastructure\Provider\Location\LegacyProviderMetricSnapshotStore;
use App\Infrastructure\Provider\Location\LegacyProviderQuotaDecisionGateway;
use App\Infrastructure\Provider\Location\SmartresponsorAddressReverseHttpBackend;
use App\Infrastructure\Provider\Location\SmartresponsorAddressSuggestBackend;
use App\Infrastructure\Provider\Location\SmartresponsorLocationMetricBackend;
use App\Infrastructure\Provider\Location\SmartresponsorProviderCostCatalogBackend;
use App\Infrastructure\Provider\Location\SmartresponsorProviderHealthSnapshotBackend;
use App\Infrastructure\Provider\Location\SmartresponsorProviderMetricSnapshotBackend;
use App\Infrastructure\Provider\Location\SmartresponsorProviderQuotaDecisionBackend;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobProgressWriterInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobRecordFactoryBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobRepositoryBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchJobStoreInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchLegacyMessageBusBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchLegacyResultBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchMessageBusInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchMessageDispatcherInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultReaderInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultStorageBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultWriterInterface;
use App\InfrastructureInterface\Provider\Location\AddressReverseGatewayInterface;
use App\InfrastructureInterface\Provider\Location\AddressReverseHttpBackendInterface;
use App\InfrastructureInterface\Provider\Location\AddressSuggestBackendInterface;
use App\InfrastructureInterface\Provider\Location\AddressSuggestGatewayInterface;
use App\InfrastructureInterface\Provider\Location\LocationMetricBackendInterface;
use App\InfrastructureInterface\Provider\Location\LocationMetricRecorderInterface;
use App\InfrastructureInterface\Provider\Location\ProviderCostCatalogBackendInterface;
use App\InfrastructureInterface\Provider\Location\ProviderCostCatalogGatewayInterface;
use App\InfrastructureInterface\Provider\Location\ProviderHealthSnapshotBackendInterface;
use App\InfrastructureInterface\Provider\Location\ProviderHealthSnapshotStoreInterface;
use App\InfrastructureInterface\Provider\Location\ProviderMetricSnapshotBackendInterface;
use App\InfrastructureInterface\Provider\Location\ProviderMetricSnapshotStoreInterface;
use App\InfrastructureInterface\Provider\Location\ProviderQuotaDecisionBackendInterface;
use App\InfrastructureInterface\Provider\Location\ProviderQuotaDecisionGatewayInterface;
use App\MessageHandler\Batch\Location\AddressBatchMessageHandler as AppAddressBatchMessageHandler;
use App\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use App\Service\Address\Location\AddressNormalizer;
use App\Service\Address\Location\AddressParser;
use App\Service\Address\Location\AddressPipeline;
use App\Service\Address\Location\AddressReverseCapability;
use App\Service\Address\Location\AddressSuggestCapability;
use App\Service\Address\Location\AddressValidator;
use App\Service\Address\Location\LocationResultFactory;
use App\Service\Batch\Location\AddressBatchJobFactory;
use App\Service\Batch\Location\LocationAddressBatchService;
use App\Service\Batch\Location\LocationAddressBatchServiceMetricDecorator;
use App\Service\Bridge\Batch\Location\LegacyAddressResultFactory;
use App\Service\Http\Location\LocationAddressReverseService;
use App\Service\Http\Location\LocationAddressSuggestService;
use App\Service\Http\Location\LocationQuotaGuard;
use App\Service\Http\Location\LocationViewFactory;
use App\Service\Http\Location\SmartresponsorLocationQuotaGuardBackend;
use App\Service\Observability\Location\LocationMetricsExportService;
use App\Service\Observability\Location\LocationProviderGovernanceAcknowledgementService;
use App\Service\Observability\Location\LocationProviderGovernanceAuditService;
use App\Service\Observability\Location\LocationProviderGovernanceCatalogService;
use App\Service\Observability\Location\LocationProviderGovernanceExecutionService;
use App\Service\Observability\Location\LocationProviderGovernanceExplanationService;
use App\Service\Observability\Location\LocationProviderGovernanceMetricsExportService;
use App\Service\Observability\Location\LocationProviderGovernanceRecommendationService;
use App\Service\Observability\Location\LocationProviderGovernanceRemediationPlanService;
use App\Service\Observability\Location\LocationProviderGovernanceReportService;
use App\Service\Observability\Location\LocationStatusReportService;
use App\Service\Provider\Location\AddressReverseResultNormalizer;
use App\Service\Provider\Location\AddressSuggestionRanker;
use App\Service\Provider\Location\LegacyAddressReverseProvider;
use App\Service\Provider\Location\LegacyAddressSuggestionProvider;
use App\Service\Provider\Location\LegacyCostAwareAddressReverseSourceCostPolicy;
use App\Service\Provider\Location\LegacyCostAwareAddressSuggestionSourceCostPolicy;
use App\Service\Provider\Location\LegacyHealthAwareAddressReverseSourceHealthPolicy;
use App\Service\Provider\Location\LegacyHealthAwareAddressSuggestionSourceHealthPolicy;
use App\Service\Provider\Location\LegacyProviderCostSignalReader;
use App\Service\Provider\Location\LegacyProviderHealthSignalReader;
use App\Service\Provider\Location\LegacyProviderQuotaSignalReader;
use App\Service\Provider\Location\LegacyQuotaAwareAddressReverseSourceQuotaPolicy;
use App\Service\Provider\Location\LegacyQuotaAwareAddressSuggestionSourceQuotaPolicy;
use App\Service\Provider\Location\OrderedAddressReverseProvider;
use App\Service\Provider\Location\OrderedAddressSuggestionProvider;
use App\Service\Provider\Location\PolicyAddressReverseSourceOrder;
use App\Service\Provider\Location\PolicyAddressSuggestionSourceOrder;
use App\Service\Provider\Location\StaticAddressReverseSourceOrder;
use App\Service\Provider\Location\StaticAddressSuggestionSourceOrder;
use App\ServiceInterface\Address\Location\AddressNormalizerInterface;
use App\ServiceInterface\Address\Location\AddressParserInterface;
use App\ServiceInterface\Address\Location\AddressPipelineInterface;
use App\ServiceInterface\Address\Location\AddressReverseCapabilityInterface;
use App\ServiceInterface\Address\Location\AddressSuggestCapabilityInterface;
use App\ServiceInterface\Address\Location\AddressValidatorInterface;
use App\ServiceInterface\Address\Location\LocationResultFactoryInterface;
use App\ServiceInterface\Batch\Location\AddressBatchJobFactoryInterface;
use App\ServiceInterface\Batch\Location\LocationAddressBatchServiceInterface;
use App\ServiceInterface\Bridge\Batch\Location\LegacyAddressResultFactoryInterface;
use App\ServiceInterface\Http\Location\LocationAddressReverseServiceInterface;
use App\ServiceInterface\Http\Location\LocationAddressSuggestServiceInterface;
use App\ServiceInterface\Http\Location\LocationQuotaGuardBackendInterface;
use App\ServiceInterface\Http\Location\LocationQuotaGuardInterface;
use App\ServiceInterface\Http\Location\LocationViewFactoryInterface;
use App\ServiceInterface\Observability\Location\LocationMetricsExportServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceAcknowledgementServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceAuditServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceCatalogServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceExecutionServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceExplanationServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceMetricsExportServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceRecommendationServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceRemediationPlanServiceInterface;
use App\ServiceInterface\Observability\Location\LocationProviderGovernanceReportServiceInterface;
use App\ServiceInterface\Observability\Location\LocationStatusReportServiceInterface;
use App\ServiceInterface\Provider\Location\AddressReverseProviderInterface;
use App\ServiceInterface\Provider\Location\AddressReverseResultNormalizerInterface;
use App\ServiceInterface\Provider\Location\AddressReverseSourceCostPolicyInterface;
use App\ServiceInterface\Provider\Location\AddressReverseSourceHealthPolicyInterface;
use App\ServiceInterface\Provider\Location\AddressReverseSourceOrderInterface;
use App\ServiceInterface\Provider\Location\AddressReverseSourceQuotaPolicyInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionProviderInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionRankerInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceCostPolicyInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceHealthPolicyInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceOrderInterface;
use App\ServiceInterface\Provider\Location\AddressSuggestionSourceQuotaPolicyInterface;
use App\ServiceInterface\Provider\Location\ProviderCostSignalReaderInterface;
use App\ServiceInterface\Provider\Location\ProviderHealthSignalReaderInterface;
use App\ServiceInterface\Provider\Location\ProviderQuotaSignalReaderInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $container): void {
    $services = $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure();

    $services->alias(AddressSuggestBackendInterface::class, SmartresponsorAddressSuggestBackend::class);
    $services->alias(AddressReverseHttpBackendInterface::class, SmartresponsorAddressReverseHttpBackend::class);
    $services->alias(LocationMetricBackendInterface::class, SmartresponsorLocationMetricBackend::class);
    $services->alias(ProviderHealthSnapshotBackendInterface::class, SmartresponsorProviderHealthSnapshotBackend::class);
    $services->alias(ProviderMetricSnapshotBackendInterface::class, SmartresponsorProviderMetricSnapshotBackend::class);
    $services->alias(ProviderCostCatalogBackendInterface::class, SmartresponsorProviderCostCatalogBackend::class);
    $services->alias(ProviderQuotaDecisionBackendInterface::class, SmartresponsorProviderQuotaDecisionBackend::class);
    $services->alias(AddressSuggestGatewayInterface::class, LegacyAddressSuggestGateway::class);
    $services->alias(AddressReverseGatewayInterface::class, LegacyAddressReverseGateway::class);
    $services->alias(LocationMetricRecorderInterface::class, LegacyLocationMetricRecorder::class);
    $services->alias(ProviderHealthSnapshotStoreInterface::class, LegacyProviderHealthSnapshotStore::class);
    $services->alias(ProviderMetricSnapshotStoreInterface::class, LegacyProviderMetricSnapshotStore::class);
    $services->alias(ProviderCostCatalogGatewayInterface::class, LegacyProviderCostCatalogGateway::class);
    $services->alias(ProviderQuotaDecisionGatewayInterface::class, LegacyProviderQuotaDecisionGateway::class);
    $services->alias(AddressSuggestionProviderInterface::class, OrderedAddressSuggestionProvider::class);
    $services->alias(AddressReverseProviderInterface::class, OrderedAddressReverseProvider::class);
    $services->alias(AddressSuggestionRankerInterface::class, AddressSuggestionRanker::class);
    $services->alias(AddressSuggestionSourceHealthPolicyInterface::class, LegacyHealthAwareAddressSuggestionSourceHealthPolicy::class);
    $services->alias(ProviderHealthSignalReaderInterface::class, LegacyProviderHealthSignalReader::class);
    $services->alias(ProviderQuotaSignalReaderInterface::class, LegacyProviderQuotaSignalReader::class);
    $services->alias(ProviderCostSignalReaderInterface::class, LegacyProviderCostSignalReader::class);
    $services->alias(AddressSuggestionSourceQuotaPolicyInterface::class, LegacyQuotaAwareAddressSuggestionSourceQuotaPolicy::class);
    $services->alias(AddressSuggestionSourceCostPolicyInterface::class, LegacyCostAwareAddressSuggestionSourceCostPolicy::class);
    $services->alias(AddressSuggestionSourceOrderInterface::class, PolicyAddressSuggestionSourceOrder::class);
    $services->alias(AddressReverseSourceHealthPolicyInterface::class, LegacyHealthAwareAddressReverseSourceHealthPolicy::class);
    $services->alias(AddressReverseSourceQuotaPolicyInterface::class, LegacyQuotaAwareAddressReverseSourceQuotaPolicy::class);
    $services->alias(AddressReverseSourceCostPolicyInterface::class, LegacyCostAwareAddressReverseSourceCostPolicy::class);
    $services->alias(AddressReverseSourceOrderInterface::class, PolicyAddressReverseSourceOrder::class);
    $services->alias(AddressReverseResultNormalizerInterface::class, AddressReverseResultNormalizer::class);
    $services->alias(AddressSuggestCapabilityInterface::class, AddressSuggestCapability::class);
    $services->alias(AddressReverseCapabilityInterface::class, AddressReverseCapability::class);
    $services->alias(LocationResultFactoryInterface::class, LocationResultFactory::class);
    $services->alias(LocationViewFactoryInterface::class, LocationViewFactory::class);
    $services->alias(LocationQuotaGuardBackendInterface::class, SmartresponsorLocationQuotaGuardBackend::class);
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
    $services->alias(LegacyAddressResultFactoryInterface::class, LegacyAddressResultFactory::class);
    $services->alias(AddressBatchJobFactoryInterface::class, AddressBatchJobFactory::class);
    $services->alias(AddressBatchJobStoreInterface::class, LegacyAddressBatchJobStore::class);
    $services->alias(AddressBatchMessageBusInterface::class, LegacyAddressBatchMessageBus::class);
    $services->alias(AddressBatchMessageDispatcherInterface::class, MessageBusAddressBatchMessageDispatcher::class);
    $services->alias(AddressBatchResultReaderInterface::class, LegacyAddressBatchResultReader::class);
    $services->alias(AddressBatchJobProgressWriterInterface::class, LegacyAddressBatchJobProgressWriter::class);
    $services->alias(AddressBatchResultWriterInterface::class, LegacyAddressBatchResultWriter::class);
    $services->alias(LocationAddressBatchServiceInterface::class, LocationAddressBatchServiceMetricDecorator::class);
    $services->alias(AddressBatchJobRepositoryBackendInterface::class, SmartresponsorAddressBatchJobRepositoryBackend::class);
    $services->alias(AddressBatchResultStorageBackendInterface::class, SmartresponsorAddressBatchResultStorageBackend::class);
    $services->alias(AddressBatchLegacyMessageBusBackendInterface::class, SmartresponsorAddressBatchLegacyMessageBusBackend::class);
    $services->alias(AddressBatchJobRecordFactoryBackendInterface::class, SmartresponsorAddressBatchJobRecordFactoryBackend::class);
    $services->alias(AddressBatchLegacyResultBackendInterface::class, SmartresponsorAddressBatchLegacyResultBackend::class);

    $services->set(SmartresponsorAddressSuggestBackend::class);

    $services->set(LegacyAddressSuggestGateway::class)
        ->args([
            service(AddressSuggestBackendInterface::class),
        ]);

    $services->set(SmartresponsorAddressReverseHttpBackend::class);

    $services->set(LegacyAddressReverseGateway::class)
        ->args([
            service(AddressReverseHttpBackendInterface::class),
        ]);

    $services->set(SmartresponsorLocationMetricBackend::class);

    $services->set(LegacyLocationMetricRecorder::class)
        ->args([
            service(LocationMetricBackendInterface::class),
        ]);

    $services->set(SmartresponsorProviderHealthSnapshotBackend::class);

    $services->set(LegacyProviderHealthSnapshotStore::class)
        ->args([
            service(ProviderHealthSnapshotBackendInterface::class),
        ]);

    $services->set(SmartresponsorProviderMetricSnapshotBackend::class);

    $services->set(LegacyProviderMetricSnapshotStore::class)
        ->args([
            service(ProviderMetricSnapshotBackendInterface::class),
        ]);

    $services->set(SmartresponsorProviderCostCatalogBackend::class);

    $services->set(LegacyProviderCostCatalogGateway::class)
        ->args([
            service(ProviderCostCatalogBackendInterface::class),
        ]);

    $services->set(SmartresponsorProviderQuotaDecisionBackend::class);

    $services->set(LegacyProviderQuotaDecisionGateway::class)
        ->args([
            service(ProviderQuotaDecisionBackendInterface::class),
        ]);

    $services->set(LegacyAddressSuggestionProvider::class)
        ->args([
            service(AddressSuggestGatewayInterface::class),
            service(LocationResultFactoryInterface::class),
        ]);

    $services->set(AddressSuggestionRanker::class);

    $services->set(LegacyProviderHealthSignalReader::class)
        ->args([
            service(ProviderHealthSnapshotStoreInterface::class),
        ]);

    $services->set(LegacyProviderQuotaSignalReader::class)
        ->args([
            service(ProviderQuotaDecisionGatewayInterface::class),
            'default',
        ]);

    $services->set(LegacyProviderCostSignalReader::class)
        ->args([
            service(ProviderCostCatalogGatewayInterface::class),
            'global',
        ]);

    $services->set(LegacyHealthAwareAddressSuggestionSourceHealthPolicy::class)
        ->args([
            service(ProviderHealthSignalReaderInterface::class),
        ]);

    $services->set(LegacyQuotaAwareAddressSuggestionSourceQuotaPolicy::class)
        ->args([
            service(ProviderQuotaSignalReaderInterface::class),
        ]);

    $services->set(LegacyCostAwareAddressSuggestionSourceCostPolicy::class)
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
            [service(LegacyAddressSuggestionProvider::class)],
            service(AddressSuggestionSourceOrderInterface::class),
            service(AddressSuggestionRankerInterface::class),
        ]);

    $services->set(LegacyAddressReverseProvider::class)
        ->args([
            service(AddressReverseGatewayInterface::class),
            service(LocationResultFactoryInterface::class),
            service(LocationMetricRecorderInterface::class),
        ]);

    $services->set(AddressReverseResultNormalizer::class);

    $services->set(LegacyHealthAwareAddressReverseSourceHealthPolicy::class)
        ->args([
            service(ProviderHealthSignalReaderInterface::class),
        ]);

    $services->set(LegacyQuotaAwareAddressReverseSourceQuotaPolicy::class)
        ->args([
            service(ProviderQuotaSignalReaderInterface::class),
        ]);

    $services->set(LegacyCostAwareAddressReverseSourceCostPolicy::class)
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
            [service(LegacyAddressReverseProvider::class)],
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

    $services->set(LegacyAddressResultFactory::class)
        ->args([
            service(AddressBatchLegacyResultBackendInterface::class),
        ]);

    $services->set(AddressBatchJobFactory::class);

    $services->set(SmartresponsorAddressBatchJobRepositoryBackend::class)
        ->args([
            service(AddressBatchJobRepositoryBackendInterface::class),
        ]);

    $services->set(SmartresponsorAddressBatchLegacyMessageBusBackend::class)
        ->args([
            service(AddressBatchLegacyMessageBusBackendInterface::class),
        ]);

    $services->set(SmartresponsorAddressBatchResultStorageBackend::class)
        ->args([
            service(AddressBatchResultStorageBackendInterface::class),
        ]);

    $services->set(LegacyAddressBatchJobStore::class)
        ->args([
            service(AddressBatchJobRepositoryBackendInterface::class),
            service(AddressBatchJobFactoryInterface::class),
        ]);

    $services->set(LegacyAddressBatchMessageBus::class)
        ->args([
            service(AddressBatchLegacyMessageBusBackendInterface::class),
        ]);

    $services->set(MessageBusAddressBatchMessageDispatcher::class)
        ->args([
            service(AddressBatchMessageBusInterface::class),
        ]);

    $services->set(LegacyAddressBatchResultReader::class)
        ->args([
            service(AddressBatchResultStorageBackendInterface::class),
        ]);

    $services->set(LegacyAddressBatchJobProgressWriter::class)
        ->args([
            service(AddressBatchJobRepositoryBackendInterface::class),
        ]);

    $services->set(LegacyAddressBatchResultWriter::class)
        ->args([
            service(AddressBatchResultStorageBackendInterface::class),
            service(LegacyAddressResultFactoryInterface::class),
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

    $services->alias(AddressBatchMessageHandlerInterface::class, AppAddressBatchMessageHandler::class);

    $services->set(GovernanceController::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceReportServiceInterface::class),
        ]);

    $services->set(GovernanceMetricsController::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceMetricsExportServiceInterface::class),
        ]);

    $services->set(AppAddressBatchMessageHandler::class)
        ->args([
            service(AddressPipelineInterface::class),
            service(AddressBatchJobProgressWriterInterface::class),
            service(AddressBatchResultWriterInterface::class),
        ]);

    $services->set(AddressBatchLegacyMessageHandler::class)
       ->args([
           service(AddressBatchMessageHandlerInterface::class),
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
                'legacy-suggest' => 'suggest',
                'legacy-reverse' => 'reverse',
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

    $services->set(AddressSuggestController::class)
        ->public()
        ->args([
            service(LocationAddressSuggestServiceInterface::class),
            service(LocationQuotaGuardInterface::class),
        ]);

    $services->set(AddressReverseController::class)
        ->public()
        ->args([
            service(LocationAddressReverseServiceInterface::class),
            service(LocationQuotaGuardInterface::class),
        ]);

    $services->set(StatusController::class)
        ->public()
        ->args([
            service(LocationStatusReportServiceInterface::class),
        ]);

    $services->set(MetricsController::class)
        ->public()
        ->args([
            service(LocationMetricsExportServiceInterface::class),
        ]);

    $services->set(GovernanceExplanationController::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceExplanationServiceInterface::class),
        ]);

    $services->set(GovernanceRecommendationController::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceRecommendationServiceInterface::class),
        ]);

    $services->set(GovernanceAuditController::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceAuditServiceInterface::class),
        ]);

    $services->set(GovernanceRemediationPlanController::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceRemediationPlanServiceInterface::class),
        ]);

    $services->set(GovernanceExecutionController::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceExecutionServiceInterface::class),
        ]);

    $services->set(GovernanceAcknowledgementController::class)
        ->public()
        ->args([
            service(LocationProviderGovernanceAcknowledgementServiceInterface::class),
        ]);
};
