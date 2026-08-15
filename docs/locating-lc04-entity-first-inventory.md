# Locating LC-04 — Entity-first inventory baseline

LC-04 establishes the entity-first inspection layer before any broader class renaming or source-tree moves.

## Scope

This wave is intentionally report-only:

- scans `src/Entity/**` and `src/EntityInterface/**`;
- records class/interface kind, namespace, file name, logical entity name, locator bucket state, and Doctrine table prefix state;
- detects duplicate logical names split across root and nested entity folders;
- highlights Doctrine table names that do not start with the canonical `location_` prefix;
- produces machine-readable reports under `report/`.

## Why this is first

The current repository contains many rich classes, but Entity and EntityInterface structure is not yet stable enough for blind renaming. The next wave should use the generated reports to choose a small, deterministic touched-file set for actual normalization.

## Generated reports

Run:

```bash
composer canon:entity-first
```

Generated files:

- `report/locating-entity-first-inventory-latest.json`
- `report/locating-entity-first-inventory-latest.csv`

## Next canonical action

LC-05 should pick one narrow entity family and normalize it end-to-end:

1. entity file name and class name;
2. matching entity interface name;
3. namespace and use references;
4. table prefix if Doctrine metadata is present;
5. tests/fixtures/demo data references when present.

No repository-wide deletion or full snapshot overwrite is part of this wave.
