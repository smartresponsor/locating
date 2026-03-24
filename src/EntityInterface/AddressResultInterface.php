<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\EntityInterface;

use App\Entity\AddressData;
use App\Entity\AddressStatus;
use App\Entity\GeoPoint;

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
