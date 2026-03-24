<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\EntityInterface;

use Smartresponsor\Entity\AddressData;
use Smartresponsor\Entity\AddressStatus;
use Smartresponsor\Entity\GeoPoint;

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
