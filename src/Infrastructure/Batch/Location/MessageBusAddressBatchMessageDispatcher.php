<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchMessageBusInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchMessageDispatcherInterface;
use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;

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
