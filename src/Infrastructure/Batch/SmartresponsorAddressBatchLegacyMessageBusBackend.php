<?php

declare(strict_types=1);

namespace App\Infrastructure\Batch\Location;

use App\Bridge\Legacy\Batch\Location\AddressBatchLegacyMessage;
use App\Bridge\Legacy\Batch\Location\AddressBatchLegacyMessageBusInterface;
use App\InfrastructureInterface\Batch\Location\AddressBatchLegacyMessageBusBackendInterface;
use App\MessageInterface\Batch\Location\AddressBatchMessageInterface;

final class SmartresponsorAddressBatchLegacyMessageBusBackend implements AddressBatchLegacyMessageBusBackendInterface
{
    public function __construct(private readonly AddressBatchLegacyMessageBusInterface $messageBus)
    {
    }

    public function dispatch(AddressBatchMessageInterface $message): void
    {
        $this->messageBus->dispatch(new AddressBatchLegacyMessage($message->jobId(), $message->payload()));
    }
}
