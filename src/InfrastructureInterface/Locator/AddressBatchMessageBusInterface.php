<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\InfrastructureInterface\Locator;

use App\Message\Locator\AddressBatchMessage;

interface AddressBatchMessageBusInterface
{
    public function dispatch(AddressBatchMessage $message): void;
}
