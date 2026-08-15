# Locating LC-03 Source Inventory and Class-Form Baseline

LC-03 adds a factual, machine-readable source inventory before broad class moves or renames.

## Purpose

The current Locating slice contains hundreds of PHP files across mixed namespaces and historical layers. A safe canonization path needs a reproducible map of:

- source path;
- namespace;
- declared symbol type and name;
- top-level Symfony-oriented layer;
- weak business-subject names;
- weak technical form suffixes;
- path/namespace mismatch candidates;
- migration bucket for future waves.

## Added commands

```bash
composer canon:source-inventory
composer canon:all
```

The inventory command writes:

- `report/locating-source-inventory-latest.json`
- `report/locating-source-inventory-latest.csv`

These reports are generated locally and should be used to choose the next exact touched-file wave.

## Expected current findings

LC-03 does not attempt to fix all findings immediately. The expected blockers are:

- `Smartresponsor\\...` transition namespace still present;
- multiple legacy bridge clusters still present;
- class names with missing `Location`/`Locator`/business subject tokens;
- class names with weak or missing technical form suffixes;
- path/namespace drift caused by historical namespace reshaping.

## Next wave recommendation

Proceed Entity-first:

1. Normalize `src/Entity/**` and `src/EntityInterface/**` naming and namespace facts.
2. Then normalize mirrored interfaces.
3. Then handle services/controllers/messages/infrastructure in separate, reviewable waves.

This keeps Doctrine/entity shape decisions isolated from runtime and service layer cleanup.
