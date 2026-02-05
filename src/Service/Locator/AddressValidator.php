<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressStatus;
use Smartresponsor\Entity\Locator\AddressValidationIssue;
use Smartresponsor\EntityInterface\Locator\AddressValidationIssueInterface;
use Smartresponsor\ServiceInterface\Locator\AddressValidatorInterface;

final class AddressValidator implements AddressValidatorInterface
{
    public function validate(AddressData $address): array
    {
        $issues = [];

        $data = $address->toArray();

        foreach (['street', 'city', 'countryCode'] as $requiredField) {
            if (trim((string)($data[$requiredField] ?? '')) === '') {
                $issues[] = AddressValidationIssue::missing($requiredField);
            }
        }

        $status = $this->decideStatus($issues, $data);

        return [
            'status' => $status,
            'issues' => $issues,
        ];
    }

    /**
     * @param AddressValidationIssueInterface[] $issues
     */
    private function decideStatus(array $issues, array $data): AddressStatus
    {
        if (count($issues) === 0) {
            return AddressStatus::VERIFIED;
        }

        $hasStreetAndCity = trim((string)($data['street'] ?? '')) !== '' &&
            trim((string)($data['city'] ?? '')) !== '';

        if ($hasStreetAndCity) {
            return AddressStatus::PARTIAL;
        }

        return AddressStatus::REJECTED;
    }
}
