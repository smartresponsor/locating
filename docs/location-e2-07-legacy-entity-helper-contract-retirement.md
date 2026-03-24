# Locating / Location — E2-07 Legacy Entity Helper Contract Retirement

This wave retires the bounded legacy helper contract cluster around address suggestion/result/validation-issue interfaces.

Retired Smartresponsor contracts:
- Smartresponsor\EntityInterface\Locator\AddressSuggestionInterface
- Smartresponsor\EntityInterface\Locator\AddressResultInterface
- Smartresponsor\EntityInterface\Locator\AddressValidationIssueInterface

Replacement App bridge contracts:
- App\Bridge\Legacy\Entity\Location\AddressSuggestionLegacyInterface
- App\Bridge\Legacy\Entity\Location\AddressResultLegacyInterface
- App\Bridge\Legacy\Entity\Location\AddressValidationIssueLegacyInterface
