<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Infrastructure\Locator;

use App\InfrastructureInterface\Locator\AddressBatchMessageBusInterface;
use App\Message\Locator\AddressBatchMessage;
use App\MessageHandler\Locator\AddressBatchMessageHandler;

/**
 * In-process message bus that immediately calls the handler.
 * Can be replaced with Symfony Messenger transport in production.
 */
final class InMemoryAddressBatchMessageBus implements AddressBatchMessageBusInterface
{
    public function __construct(
        private AddressBatchMessageHandler $handler
    ) {
    }

    public function dispatch(AddressBatchMessage $message): void
    {
        ($this->handler)($message);
    }
}
