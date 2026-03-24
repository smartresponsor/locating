<?php

declare(strict_types=1);

namespace App\Infrastructure\Batch\Location;

use App\InfrastructureInterface\Batch\Location\AddressBatchLegacyResultBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;
use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressResult;
use Smartresponsor\Entity\Locator\AddressStatus;
use Smartresponsor\Entity\Locator\AddressValidationIssue;

final class SmartresponsorAddressBatchLegacyResultBackend implements AddressBatchLegacyResultBackendInterface
{
    public function create(string $status, ?array $address, array $issues): AddressBatchResultRecordInterface
    {
        $legacy = AddressResult::create(
            $this->mapStatus($status),
            null === $address ? null : AddressData::fromArray($address),
            array_map(
                static fn (array $issue): AddressValidationIssue => new AddressValidationIssue(
                    (string) $issue['field'],
                    (string) $issue['code'],
                    (string) $issue['message'],
                ),
                $issues,
            ),
        );

        return new SmartresponsorAddressBatchResultRecord($legacy);
    }

    private function mapStatus(string $status): AddressStatus
    {
        try {
            return AddressStatus::from($status);
        } catch (\ValueError) {
            return AddressStatus::REJECTED;
        }
    }
}
