<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\EntityInterface\Locator;

use App\Entity\Locator\AddressData;
use App\Entity\Locator\AddressStatus;
use App\Entity\Locator\GeoPoint;

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
