<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchMessageBusInterface;
use App\Locating\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;

final class InMemoryAddressBatchMessageBus implements AddressBatchMessageBusInterface
{
    public function __construct(
        private AddressBatchMessageHandlerInterface $handler,
    ) {
    }

    public function dispatch(AddressBatchMessageInterface $message): void
    {
        ($this->handler)($message);
    }
}
