# Locating LC-42 — Service Locator CostCapPolicy legacy path normalization

LC-42 continues the provider-adjacent legacy retirement track for `Smartresponsor\Service\Locator`.

## Scope

- Move `src/Service/Locator/CostCapPolicy.php` to `src/Service/Provider/Location/CostCapPolicyService.php`.
- Change namespace from `Smartresponsor\Service\Locator` to `App\Service\Provider\Location`.
- Give the class an explicit service-form suffix: `CostCapPolicyService`.
- Preserve the existing bridge contract `CostCapPolicyLegacyInterface`.
- Retire only the old touched path through the apply script, with backup.

## Out of scope

- No mass provider-track refactor.
- No full repository cleanup.
- No cumulative snapshot delivery.
