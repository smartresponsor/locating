<?php

declare(strict_types=1);

namespace App\Locating\Contract\Location;

interface LocatorProviderContract
{
    public function getName(): string;

    public function getPriority(): int;

    public function isHealthy(): bool;

    public function getLocator(): LocatorContract;
}
