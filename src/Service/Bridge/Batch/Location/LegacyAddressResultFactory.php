<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Service\Bridge\Batch\Location;

use App\EntityInterface\Location\AddressIssueInterface;
use App\EntityInterface\Location\AddressPipelineResultInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchLegacyResultBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;
use App\ServiceInterface\Bridge\Batch\Location\LegacyAddressResultFactoryInterface;

final class LegacyAddressResultFactory implements LegacyAddressResultFactoryInterface
{
    public function __construct(private readonly AddressBatchLegacyResultBackendInterface $backend)
    {
    }

    public function create(AddressPipelineResultInterface $result): AddressBatchResultRecordInterface
    {
        $address = $result->address();

        return $this->backend->create(
            $result->status(),
            null === $address ? null : $address->toArray(),
            $this->mapIssues($result),
        );
    }

    /** @return list<array{field:string,code:string,message:string}> */
    private function mapIssues(AddressPipelineResultInterface $result): array
    {
        return array_map(
            static fn (AddressIssueInterface $issue): array => [
                'field' => $issue->field(),
                'code' => $issue->code(),
                'message' => $issue->message(),
            ],
            $result->issues(),
        );
    }
}
