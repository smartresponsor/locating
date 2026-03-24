# Location r18 — batch consumer switched to App-owned address pipeline

This wave connects the App-owned address preparation pipeline to a real operational consumer.

## Changes
- `Smartresponsor\MessageHandler\Locator\AddressBatchMessageHandler` now depends on `App\ServiceInterface\Address\Location\AddressPipelineInterface`.
- Added `App\Service\Bridge\Batch\Location\LegacyAddressResultFactory` to project App pipeline results back into legacy batch storage shape.
- Repaired `config/services.php` so App pipeline aliases live inside the container closure and the batch handler is wired explicitly.

## Effect
The App address pipeline is no longer only library-local. It is now used by the batch message-handling flow while batch storage remains transition-safe and legacy-compatible.
