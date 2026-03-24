<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Infrastructure\Batch\Location;

use App\InfrastructureInterface\Batch\Location\AddressBatchLegacyMessageBusBackendInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchMessageBusInterface;
use App\MessageInterface\Batch\Location\AddressBatchMessageInterface;

final class LegacyAddressBatchMessageBus implements AddressBatchMessageBusInterface
{
    public function __construct(
        private AddressBatchLegacyMessageBusBackendInterface $messageBus,
    ) {
    }

    public function dispatch(AddressBatchMessageInterface $message): void
    {
        $this->messageBus->dispatch($message);
    }
}
