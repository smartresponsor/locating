<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressResult;
use Smartresponsor\ServiceInterface\Locator\AddressGeocodeBridgeInterface;
use Smartresponsor\ServiceInterface\Locator\AddressProviderRouterInterface;
use Smartresponsor\ServiceInterface\Locator\AddressQuotaGuardInterface;

/**
 * Optional geocoding step that uses a provider router and per-tenant quota guard.
 */
final class AddressGeocodeBridge implements AddressGeocodeBridgeInterface
{
    private AddressProviderRouterInterface $router;

    private ?AddressQuotaGuardInterface $quotaGuard;

    public function __construct(
        AddressProviderRouterInterface $router,
        ?AddressQuotaGuardInterface $quotaGuard = null
    ) {
        $this->router = $router;
        $this->quotaGuard = $quotaGuard;
    }

    public function enrich(AddressData $address, AddressResult $result): AddressResult
    {
        if ($this->quotaGuard !== null) {
            $allowed = $this->quotaGuard->isAllowed(AddressQuotaGuard::OPERATION_GEOCODE);
            if (!$allowed) {
                return $result;
            }
        }

        $componentMap = $address->toArray();

        $point = $this->router->geocode($componentMap);
        if ($point === null) {
            return $result;
        }

        $refClass = new \ReflectionClass($result);
        if (!$refClass->hasMethod('create')) {
            return $result;
        }

        $factory = $refClass->getMethod('create');

        /** @var AddressResult $new */
        $new = $factory->invoke(
            null,
            $result->status(),
            $result->addressData(),
            $result->issues(),
            $point,
            $this->router->providerKey()
        );

        return $new;
    }
}
