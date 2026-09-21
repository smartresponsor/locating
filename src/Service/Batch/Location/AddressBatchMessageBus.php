<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Batch\Location;

use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchMessageBusBackendInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchMessageBusInterface;

final class AddressBatchMessageBus implements AddressBatchMessageBusInterface
{
    public function __construct(
        private AddressBatchMessageBusBackendInterface $messageBus,
    ) {
    }

    public function dispatch(AddressBatchMessageInterface $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
