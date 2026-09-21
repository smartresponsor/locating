<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Batch\Location;

use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;

interface AddressBatchMessageBusBackendInterface
{
    public function dispatch(AddressBatchMessageInterface $message): void;
}
