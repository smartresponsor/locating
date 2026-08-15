<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\InfrastructureInterface\Batch\Location;

use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;

interface AddressBatchMessageDispatcherInterface
{
    public function dispatch(AddressBatchMessageInterface $message): void;
}
