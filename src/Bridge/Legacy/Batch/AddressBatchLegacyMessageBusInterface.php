<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Batch\Location;

interface AddressBatchLegacyMessageBusInterface
{
    public function dispatch(AddressBatchLegacyMessage $message): void;
}
