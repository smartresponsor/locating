<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\InfrastructureInterface;

use Smartresponsor\Message\AddressBatchMessage;

interface AddressBatchMessageBusInterface
{
    public function dispatch(AddressBatchMessage $message): void;
}
