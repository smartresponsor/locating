<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Contract\Location;

interface LocatorProviderLegacyInterface
{
    public function getName(): string;

    public function getPriority(): int;

    public function isHealthy(): bool;

    public function getLocator(): LocatorLegacyInterface;
}
