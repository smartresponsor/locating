<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultBackendInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultRecordInterface;
use App\Locating\ModelInterface\Location\AddressIssueInterface;
use App\Locating\ModelInterface\Location\AddressPipelineResultInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressResultFactoryInterface;

final class AddressResultFactory implements AddressResultFactoryInterface
{
    public function __construct(private readonly AddressBatchResultBackendInterface $backend)
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
