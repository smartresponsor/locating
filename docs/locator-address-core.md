# Locator / Location component — Address Core (L1)

This document describes the L1 address core inside the Locator component.

Scope covered:

- AddressInput / AddressInputInterface: immutable input DTO for a single address attempt.
- AddressData / AddressDataInterface: normalized address container.
- AddressStatus + AddressValidationIssue + AddressResult / AddressResultInterface: result model.
- AddressParser + AddressParserGeneric with AddressParserCountryStrategyInterface.
- AddressNormalizer and AddressValidator.
- AddressPipeline: orchestration from input to result.

The goal of L1 is to provide a production-grade, framework-agnostic core that can be reused
by HTTP controllers, message handlers, or CLI tooling without additional coupling.
