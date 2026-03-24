<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Batch\Location;

use App\Message\Batch\Location\AddressBatchMessage as AppAddressBatchMessage;
use App\MessageHandlerInterface\Batch\Location\AddressBatchMessageHandlerInterface;

/**
 * Transitional adapter that forwards the legacy bridge message to the App-owned batch handler.
 */
final class AddressBatchLegacyMessageHandler
{
    public function __construct(
        private readonly AddressBatchMessageHandlerInterface $handler,
    ) {
    }

    public function __invoke(AddressBatchLegacyMessage $message): void
    {
        ($this->handler)(new AppAddressBatchMessage($message->jobId(), $message->payload()));
    }
}
