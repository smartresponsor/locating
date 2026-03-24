<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Service;

use Smartresponsor\Entity\GeoPoint;
use Smartresponsor\InfrastructureInterface\AddressProviderBridgeInterface;
use Smartresponsor\ServiceInterface\AddressProviderRouterInterface;

/**
 * Simple multi-provider router with failover.
 */
final class AddressProviderRouter implements AddressProviderRouterInterface
{
    /**
     * @var AddressProviderBridgeInterface[]
     */
    private array $providers;

    private ?string $lastProviderKey = null;

    /**
     * @param iterable<AddressProviderBridgeInterface> $providers
     */
    public function __construct(iterable $providers)
    {
        $this->providers = [];
        foreach ($providers as $provider) {
            $this->providers[] = $provider;
        }
    }

    public function geocode(array $componentMap): ?GeoPoint
    {
        $this->lastProviderKey = null;

        foreach ($this->providers as $provider) {
            try {
                $point = $provider->geocode($componentMap);
            } catch (\Throwable) {
                continue;
            }

            if ($point !== null) {
                $this->lastProviderKey = $provider->providerKey();

                return $point;
            }
        }

        return null;
    }

    public function providerKey(): ?string
    {
        return $this->lastProviderKey;
    }
}
