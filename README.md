# Locating

`locating/location` is the SmartResponsor stateless location-processing component for address parsing, normalization, validation, suggestion, reverse lookup, provider routing, and geospatial support.

## Component boundary

Locating owns computation and provider orchestration. It does not own durable Doctrine address persistence. Durable address records belong to Addressing; the host application coordinates Locating validation with Addressing persistence.

The canonical PHP namespace is `App\Locating\` and Composer maps it directly to `src/`. Namespace and filesystem paths are required to match PSR-4 exactly.

The current runtime is organized around canonical component layers such as:

- `Model` / `ModelInterface` for immutable inputs, results, and value shapes.
- `Service` / `ServiceInterface` for application behavior and contracts.
- `Infrastructure` / `InfrastructureInterface` for runtime backends.
- `Integration` for external provider/client implementations.
- `ReadModel` / `ReadModelInterface` for observability and governance projections.
- `Controller` / `ControllerInterface`, `Message`, and `MessageHandler` for transport boundaries.

Legacy `Smartresponsor\*`, `Bridge/Legacy`, parallel `*/Locator` runtime trees, and Entity-shaped non-persistent value objects are retired from the active component surface.

## Validation

Use the repository-declared Composer scripts:

```text
composer validate
composer run-script cs:check
composer run-script lint
composer run-script test
composer run-script canon
composer run-script stan
```

`composer run-script canon` verifies the component namespace/path contract and the cumulative Locating normalization gates under `.gate/check/` and `tools/canon/`.

Generated `report/locating-*-latest.*` files are diagnostic outputs and are intentionally not source-controlled because they include run timestamps. Historical migration evidence remains under `report/` and `docs/` where it is explicitly retained.

## Runtime notes

Provider-specific runtime and infrastructure live under `Provider/Location` rather than a parallel `Locator` hierarchy. Address pipeline consumers should depend on `App\Locating\ServiceInterface\AddressPipelineInterface` rather than the concrete implementation.

Deployment configuration belongs under `deploy/`; legacy root/archive deployment artifacts are not part of the runtime contract.

## Historical documentation

The repository contains historical engineering and canonization notes under `docs/` and `report/legacy/`. They document how the consolidated source was reduced to the current Locating component but are not authoritative over the current code, Composer configuration, executable gates, or tests.
