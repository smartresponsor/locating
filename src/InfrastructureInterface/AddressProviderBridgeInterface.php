<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\InfrastructureInterface;

use Smartresponsor\Entity\GeoPoint;

interface AddressProviderBridgeInterface
{
    /**
     * @param array<string,string> $componentMap
     */
    public function geocode(array $componentMap): ?GeoPoint;

    public function providerKey(): string;
}
