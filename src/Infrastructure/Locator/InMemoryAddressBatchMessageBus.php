<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Infrastructure\Locator;

use Smartresponsor\InfrastructureInterface\Locator\AddressBatchMessageBusInterface;
use Smartresponsor\Message\Locator\AddressBatchMessage;
use Smartresponsor\MessageHandler\Locator\AddressBatchMessageHandler;

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
