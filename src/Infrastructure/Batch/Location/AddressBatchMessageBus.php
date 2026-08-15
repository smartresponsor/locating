<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchMessageBusBackendInterface;
use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchMessageBusInterface;
use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;

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
