<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location\Config;

use App\Locating\InfrastructureInterface\Provider\Location\Config\LocatorConfigInterface;

final class LocatorConfig implements LocatorConfigInterface
{
    public function __construct(public readonly string $strategy = 'osm', public readonly ?string $googleApiKey = null, public readonly string $nominatimBaseUrl = 'https://nominatim.openstreetmap.org', public readonly ?string $nominatimEmail = null)
    {
    }
}
