# Location R17 — App-owned address pipeline extraction

This wave extracts a live address preparation chain from legacy `Smartresponsor\Service\Locator\...` into App-owned Symfony-oriented services.

Added App-owned runtime chain:
- `App\Entity\Location\AddressInput`
- `App\Entity\Location\AddressIssue`
- `App\Entity\Location\AddressPipelineResult`
- `App\Service\Address\Location\AddressParser`
- `App\Service\Address\Location\AddressNormalizer`
- `App\Service\Address\Location\AddressValidator`
- `App\Service\Address\Location\AddressPipeline`

Also strengthened `App\Entity\Location\AddressView` with typed getters so new App services and tests do not depend on ad-hoc array access.

This is a controlled business-chain extraction, not a destructive replacement of the remaining legacy locator graph.
