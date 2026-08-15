# Locating LC-38 — Service Locator ProviderKeyRotation legacy path normalization

LC-38 continues the provider-side retirement track after ProviderKeyVault.

## Scope

Touched production class:

- `src/Service/Locator/ProviderKeyRotation.php`
- `src/Service/Provider/Location/ProviderKeyRotationService.php`

## Canonical decision

`ProviderKeyRotation` is provider-side key lifecycle infrastructure, not a generic root-level `Locator` service. It now lives under the Symfony-oriented provider service layer:

- namespace: `App\Service\Provider\Location`
- class: `ProviderKeyRotationService`

The existing bridge contract remains unchanged:

- `App\ServiceInterface\Provider\Location\Credential\ProviderKeyRotationInterface`

## Retirement rule

The old legacy path is retired only by the apply script and only by exact path match:

- `src/Service/Locator/ProviderKeyRotation.php`

The script backs up the retired file under `.patch-backup/locating-lc38-provider-key-rotation-legacy-path/` before removal.

## Verification

Run:

```bash
composer dump-autoload
composer lint
composer canon:service-locator-provider-key-rotation-legacy-path
composer canon:all
```
