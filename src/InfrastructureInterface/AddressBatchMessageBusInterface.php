<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\InfrastructureInterface;

use App\Message\AddressBatchMessage;

interface AddressBatchMessageBusInterface
{
    public function dispatch(AddressBatchMessage $message): void;
}
