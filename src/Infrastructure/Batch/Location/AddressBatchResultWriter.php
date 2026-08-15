<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultStorageBackendInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchResultWriterInterface;
use App\Locating\ModelInterface\Location\AddressPipelineResultInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressResultFactoryInterface;

final class AddressBatchResultWriter implements AddressBatchResultWriterInterface
{
    public function __construct(
        private AddressBatchResultStorageBackendInterface $resultStorage,
        private AddressResultFactoryInterface $resultFactory,
    ) {
    }

    public function appendPipelineResult(string $jobId, AddressPipelineResultInterface $result): void
    {
        $this->resultStorage->appendResult($jobId, $this->resultFactory->create($result));
    }
}
