# E2-M5 / Dead interface root purge

This macro pack removes dead legacy interface declarations that no longer participate in the active App runtime and have no remaining references outside their own files.

## Removed service interfaces
- AddressCanonicalizerInterface
- AddressInterface
- BatchSchedulerInterface
- ConfidenceScoreInterface
- CostAwarenessInterface
- EnvInterface
- ErasureJobInterface
- GeohashInterface
- JitterInterface
- LatencyForecastInterface
- PiiAnonymizerInterface
- RateLimiterMemoryInterface
- RateLimiterRedisInterface
- RedactorInterface
- RouteDecisionInterface

## Removed infrastructure interfaces
- ApiKeyAuthInterface
- ArrayTenantConfigRepositoryInterface
- CurlHttpClientInterface
- FixtureHttpClientInterface
- JsonlReaderInterface
- JsonlWriterInterface
- KernelInterface
- NullMetricRecorderInterface
- RedisCacheInterface
- TracingLocatorInterface

## Effect
This removes a large batch of dead `Smartresponsor` interface declarations without touching active App-owned runtime paths.
