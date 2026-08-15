<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\Model\Location\AddressIssue;
use App\Locating\Model\Location\AddressPipelineResult;
use App\Locating\ModelInterface\Location\AddressIssueInterface;
use App\Locating\ModelInterface\Location\AddressPipelineResultInterface;
use App\Locating\ModelInterface\Location\AddressViewInterface;
use App\Locating\ServiceInterface\Address\Location\AddressValidatorInterface;

final class AddressValidator implements AddressValidatorInterface
{
    public function validate(AddressViewInterface $address): AddressPipelineResultInterface
    {
        $issues = [];
        $data = $address->toArray();

        foreach (['street', 'city', 'countryCode'] as $requiredField) {
            if ('' === trim((string) ($data[$requiredField] ?? ''))) {
                $issues[] = AddressIssue::missing($requiredField);
            }
        }

        return new AddressPipelineResult(
            $this->decideStatus($issues, $address),
            $this->shouldKeepAddress($issues, $address) ? $address : null,
            $issues,
        );
    }

    /**
     * @param list<AddressIssueInterface> $issues
     */
    private function decideStatus(array $issues, AddressViewInterface $address): string
    {
        if ([] === $issues) {
            return AddressPipelineResult::STATUS_VERIFIED;
        }

        $hasStreetAndCity = '' !== trim($address->street()) && '' !== trim($address->city());

        if ($hasStreetAndCity) {
            return AddressPipelineResult::STATUS_PARTIAL;
        }

        return AddressPipelineResult::STATUS_REJECTED;
    }

    /**
     * @param list<AddressIssueInterface> $issues
     */
    private function shouldKeepAddress(array $issues, AddressViewInterface $address): bool
    {
        return AddressPipelineResult::STATUS_REJECTED !== $this->decideStatus($issues, $address);
    }
}
