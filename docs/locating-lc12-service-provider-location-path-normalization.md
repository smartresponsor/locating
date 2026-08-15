# Locating LC-12 — Service Provider/Location path normalization

LC-12 continues the App-owned service-family normalization after Address, Http, Batch, and Observability.

## Scope

This wave moves the Provider service slice into physical paths matching the already declared namespaces:

- `App\Service\Provider\Location` → `src/Service/Provider/Location/`
- `App\ServiceInterface\Provider\Location` → `src/ServiceInterface/Provider/Location/`

The namespace declarations are intentionally preserved. This is a path/PSR-4 alignment wave, not a behavior rewrite.

## Explicitly out of scope

- `Smartresponsor\Service\Locator` retirement
- broad service renaming
- controller/API changes
- provider behavior changes
- root repository cleanup

## Gate

Run:

```bash
composer canon:service-provider-location-path
```

The gate fails if old Provider root-level files still exist or if the expected `Location/` files are missing.
