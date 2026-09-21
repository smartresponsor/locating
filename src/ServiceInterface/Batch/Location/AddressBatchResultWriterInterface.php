<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\ModelInterface\Location\AddressPipelineResultInterface;

interface AddressBatchResultWriterInterface
{
    public function appendPipelineResult(string $jobId, AddressPipelineResultInterface $result): void;
}
