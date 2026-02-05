<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\ServiceInterface\Locator;

use Smartresponsor\Entity\Locator\GeoPoint;

interface AddressProviderRouterInterface
{
    /**
     * @param array<string,string> $componentMap
     */
    public function geocode(array $componentMap): ?GeoPoint;

    public function providerKey(): ?string;
}
