# Location R21 — legacy address pipeline collapse

## Scope
This wave removes the now-orphaned legacy `Smartresponsor\\Service\\Locator` address preparation cluster:

- `AddressParser`
- `AddressParserGeneric`
- `AddressNormalizer`
- `AddressValidator`
- `AddressPipeline`
- `AddressPipelineMetricDecorator`
- their matching `ServiceInterface\\Locator` contracts
- their dedicated legacy tests

## Why this is safe
The active Symfony wiring already uses the App-owned address pipeline introduced in previous waves:

- `App\\Service\\Address\\Location\\AddressParser`
- `App\\Service\\Address\\Location\\AddressNormalizer`
- `App\\Service\\Address\\Location\\AddressValidator`
- `App\\Service\\Address\\Location\\AddressPipeline`

The active batch runtime consumer also depends on the App-owned pipeline contract:

- `App\\ServiceInterface\\Address\\Location\\AddressPipelineInterface`

No active routing or service wiring referenced the removed legacy pipeline cluster anymore.

## Effect
This wave collapses another parallel `Locator` business surface and narrows the remaining legacy runtime area.
