<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Geo;

interface LocatorProviderInterface
{
    public function getName(): string;

    public function getPriority(): int;

    public function isHealthy(): bool;

    public function getLocator(): LocatorInterface;
}
