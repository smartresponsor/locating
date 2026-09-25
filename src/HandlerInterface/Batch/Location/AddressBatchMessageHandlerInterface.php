<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\HandlerInterface\Batch\Location;

use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;

interface AddressBatchMessageHandlerInterface
{
    public function __invoke(AddressBatchMessageInterface $message): void;
}
