<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Location\Batch;

use App\Locating\Model\Location\Batch\AddressBatchMessage;

interface AddressBatchMessageBusInterface
{
    public function dispatch(AddressBatchMessage $message): void;
}
