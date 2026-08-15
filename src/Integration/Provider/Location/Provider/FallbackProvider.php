<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Provider;

use App\Locating\Contract\Location\LocatorContract;
use App\Locating\Contract\Location\LocatorProviderContract;
use App\Locating\Integration\Provider\Location\Resilience\FallbackLocator;

final class FallbackProvider implements LocatorProviderContract
{
    public function __construct(private int $priority = 0)
    {
    }

    public function getName(): string
    {
        return 'fallback';
    }

    public function getPriority(): int
    {
        return $this->priority;
    }

    public function isHealthy(): bool
    {
        return true;
    }

    public function getLocator(): LocatorContract
    {
        return new FallbackLocator();
    }
}
