# Locating LC-37 — Service Locator ProviderKeyVault legacy path normalization

LC-37 continues the provider-side legacy retirement track by moving the provider key vault service out of `Smartresponsor\Service\Locator` and into the canonical Symfony-oriented provider service layer.

## Scope

- Retire `src/Service/Locator/ProviderKeyVault.php` with backup during apply.
- Add `src/Service/Provider/Location/ProviderKeyVaultService.php`.
- Preserve `App\ServiceInterface\Provider\Location\Credential\ProviderKeyVaultInterface` as the compatibility contract.
- Keep the wave touched-only and avoid cumulative repository overwrite.

## Follow-up

After this wave, continue with adjacent provider key and contract services one by one: `ProviderKeyRotation`, `ProviderContractVersion`, `ProviderContractGolden`, `ProviderSandbox`, and remaining provider policy/runtime collaborators.
