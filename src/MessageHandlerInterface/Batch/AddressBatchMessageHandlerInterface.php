<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\MessageHandlerInterface\Batch\Location;

use App\MessageInterface\Batch\Location\AddressBatchMessageInterface;

interface AddressBatchMessageHandlerInterface
{
    public function __invoke(AddressBatchMessageInterface $message): void;
}
