<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\EntityInterface\Locator;

use Smartresponsor\Entity\Locator\AddressData;
use Smartresponsor\Entity\Locator\AddressStatus;
use Smartresponsor\Entity\Locator\GeoPoint;

interface AddressResultInterface
{
    public function status(): AddressStatus;

    /**
     * @return AddressData|null
     */
    public function addressData(): ?AddressData;

    /**
     * @return AddressValidationIssueInterface[]
     */
    public function issues(): array;

    public function geoPoint(): ?GeoPoint;

    public function providerKey(): ?string;

    public function toArray(): array;
}
