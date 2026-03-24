<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Infrastructure;

use Smartresponsor\InfrastructureInterface\AddressBatchMessageBusInterface;
use Smartresponsor\Message\AddressBatchMessage;
use Smartresponsor\MessageHandler\AddressBatchMessageHandler;

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
