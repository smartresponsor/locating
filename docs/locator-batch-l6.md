# Locator L6 – Batch and async pipeline sketch

This envelope adds a production-ready layout for batch validation with an async-ready processing model.

Key pieces:

- AddressBatchJobStatus enum
  - App\Entity\Locator\AddressBatchJobStatus

- Batch job contract and entity
  - App\EntityInterface\Locator\AddressBatchJobInterface
  - App\Entity\Locator\AddressBatchJob

- Job repository
  - App\InfrastructureInterface\Locator\AddressBatchJobRepositoryInterface
  - App\Infrastructure\Locator\InMemoryAddressBatchJobRepository

- Result storage
  - App\InfrastructureInterface\Locator\AddressBatchResultStorageInterface
  - App\Infrastructure\Locator\InMemoryAddressBatchResultStorage

- Message and bus
  - App\Message\Locator\AddressBatchMessage
  - App\InfrastructureInterface\Locator\AddressBatchMessageBusInterface
  - App\MessageHandler\Locator\AddressBatchMessageHandler
  - App\Infrastructure\Locator\InMemoryAddressBatchMessageBus

- Batch service
  - App\ServiceInterface\Locator\AddressBatchServiceInterface
  - App\Service\Locator\AddressBatchService

Flow:

1. Application calls AddressBatchServiceInterface::createJob(tenantId, itemList).
2. Service creates AddressBatchJob and persists it.
3. For each item it dispatches AddressBatchMessage with jobId and payload.
4. InMemoryAddressBatchMessageBus calls AddressBatchMessageHandler immediately.
   - A real deployment can route the message via Symfony Messenger transport.
5. Handler builds AddressInput, calls AddressPipelineInterface, appends result and updates job progress.

The layout is stable for L6 and can be wired with real persistence and a real message bus without changing interfaces.
