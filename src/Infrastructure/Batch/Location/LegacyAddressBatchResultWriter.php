<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Batch\Location;

use App\EntityInterface\Location\AddressPipelineResultInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultStorageBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchResultWriterInterface;
use App\ServiceInterface\Bridge\Batch\Location\LegacyAddressResultFactoryInterface;

final class LegacyAddressBatchResultWriter implements AddressBatchResultWriterInterface
{
    public function __construct(
        private AddressBatchResultStorageBackendInterface $resultStorage,
        private LegacyAddressResultFactoryInterface $legacyResultFactory,
    ) {
    }

    public function appendPipelineResult(string $jobId, AddressPipelineResultInterface $result): void
    {
        $this->resultStorage->appendResult($jobId, $this->legacyResultFactory->create($result));
    }
}
