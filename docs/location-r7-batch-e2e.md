# Location R7 – Batch and endpoint hardening

This envelope focuses on batch address processing and geocode endpoint behaviour.

- Fixed AddressBatchServiceInterface namespace to use App\\EntityInterface.
- Fixed BatchService wiring to App\\Service\\Locator\\BatchService.
- Fixed BatchGeocodeEndpointInterface namespace and added basic PHPDoc for payload and result shape.
- Fixed BatchGeocodeEndpoint implementation:
  - Added explicit imports for ResultCacheInterface and BatchGeocodeEndpointInterface.
  - Repaired normalize() implementation and deterministic lat/lon hashing.
- Updated tests:
  - BatchServiceTest now extends PHPUnit TestCase and uses App\\Service\\Locator\\BatchService.
  - BatchGeocodeEndpointTest now validates both coordinate shape and cache behaviour.
  - PriorityBatchQueueTest now targets App\\Entity\\Locator\\PriorityQueue and asserts priority ordering.
