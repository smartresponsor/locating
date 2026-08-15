# Locating LC-32 — Service Locator ProviderAuthzPolicy legacy path normalization

LC-32 continues the provider-side retirement track started after LC-27.

## Scope

This wave moves the provider authorization policy out of the generic legacy `Smartresponsor\Service\Locator` namespace into the canonical provider service layer.

## Touched runtime move

- `src/Service/Locator/ProviderAuthzPolicy.php`
- `src/Service/Provider/Location/ProviderAuthzPolicyService.php`

The class keeps the existing bridge contract:

- `App\Bridge\Legacy\Service\Location\ProviderAuthzPolicyLegacyInterface`

## Rule

The old path must not remain after the touched-file overlay has been applied. The apply script retires only the explicitly listed old file and stores a backup under `.patch-backup`.
