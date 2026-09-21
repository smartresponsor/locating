<?php

declare(strict_types=1);

namespace App\Locating\Service\Batch\Location;

use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\AddressResult;
use App\Locating\Model\Location\AddressStatus;
use App\Locating\Model\Location\AddressValidationIssue;
use App\Locating\Model\Location\Batch\AddressBatchResultRecord;
use App\Locating\ModelInterface\Location\AddressBatchResultRecordInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchResultBackendInterface;

final class AddressBatchResultBackend implements AddressBatchResultBackendInterface
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

        return new AddressBatchResultRecord($legacy);
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
