<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Batch\Location;

use App\Locating\InfrastructureInterface\Batch\Location\AddressBatchMessageBusBackendInterface;
use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;
use App\Locating\Model\Location\Batch\AddressBatchMessage;
use App\Locating\Model\Location\Batch\AddressBatchMessageBusInterface;

final class AddressBatchMessageBusBackend implements AddressBatchMessageBusBackendInterface
{
    public function __construct(private readonly AddressBatchMessageBusInterface $messageBus)
    {
    }

    public function dispatch(AddressBatchMessageInterface $message): void
    {
        $this->messageBus->dispatch(new AddressBatchMessage($message->jobId(), $message->payload()));
    }
}
