<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Service\Batch\Location;

use App\Locating\HandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;
use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;
use App\Locating\ServiceInterface\Batch\Location\AddressBatchMessageBusInterface;

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
