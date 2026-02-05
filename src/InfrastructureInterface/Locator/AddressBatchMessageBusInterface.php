<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\InfrastructureInterface\Locator;

use Smartresponsor\Message\Locator\AddressBatchMessage;

interface AddressBatchMessageBusInterface
{
    public function dispatch(AddressBatchMessage $message): void;
}
