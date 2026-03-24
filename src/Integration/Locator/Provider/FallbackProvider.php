<?php

declare(strict_types=1);

namespace Smartresponsor\Integration\Locator\Provider;

use App\Bridge\Legacy\Contract\Location\LocatorLegacyInterface;
use App\Bridge\Legacy\Contract\Location\LocatorProviderLegacyInterface;
use Smartresponsor\Integration\Locator\Fallback\FallbackLocator;

final class FallbackProvider implements LocatorProviderLegacyInterface
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

    public function getLocator(): LocatorLegacyInterface
    {
        return new FallbackLocator();
    }
}
