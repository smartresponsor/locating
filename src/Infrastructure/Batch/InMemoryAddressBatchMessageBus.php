<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Batch\Location;

use App\InfrastructureInterface\Batch\Location\AddressBatchMessageBusInterface;
use App\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use App\MessageInterface\Batch\Location\AddressBatchMessageInterface;

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
