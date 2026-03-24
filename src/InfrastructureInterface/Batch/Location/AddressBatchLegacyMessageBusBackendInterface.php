<?php

declare(strict_types=1);

namespace App\InfrastructureInterface\Batch\Location;

use App\MessageInterface\Batch\Location\AddressBatchMessageInterface;

interface AddressBatchLegacyMessageBusBackendInterface
{
    public function dispatch(AddressBatchMessageInterface $message): void;
}
