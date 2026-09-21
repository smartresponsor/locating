<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Batch\Location;

use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchMessageBusInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchMessageDispatcherInterface;

final class MessageBusAddressBatchMessageDispatcher implements AddressBatchMessageDispatcherInterface
{
    public function __construct(
        private AddressBatchMessageBusInterface $messageBus,
    ) {
    }

    public function dispatch(AddressBatchMessageInterface $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
