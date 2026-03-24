<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\InfrastructureInterface\Batch\Location;

use App\MessageInterface\Batch\Location\AddressBatchMessageInterface;

interface AddressBatchMessageDispatcherInterface
{
    public function dispatch(AddressBatchMessageInterface $message): void;
}
