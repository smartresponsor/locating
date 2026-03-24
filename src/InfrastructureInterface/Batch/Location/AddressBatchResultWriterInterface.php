<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\InfrastructureInterface\Batch\Location;

use App\EntityInterface\Location\AddressPipelineResultInterface;

interface AddressBatchResultWriterInterface
{
    public function appendPipelineResult(string $jobId, AddressPipelineResultInterface $result): void;
}
