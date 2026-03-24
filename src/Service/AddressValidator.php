<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Service;

use App\Entity\AddressData;
use App\Entity\AddressStatus;
use App\Entity\AddressValidationIssue;
use App\EntityInterface\AddressValidationIssueInterface;
use App\ServiceInterface\AddressValidatorInterface;

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
