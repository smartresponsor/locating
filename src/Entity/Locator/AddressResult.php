<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Entity\Locator;

use App\Bridge\Legacy\Entity\Location\AddressResultLegacyInterface;
use App\Bridge\Legacy\Entity\Location\AddressValidationIssueLegacyInterface;

final class AddressResult implements AddressResultLegacyInterface
{
    /**
     * @param AddressValidationIssueLegacyInterface[] $issues
     */
    public function __construct(
        private AddressStatus $status,
        private ?AddressData $addressData,
        private array $issues,
        private ?GeoPoint $geoPoint,
        private ?string $providerKey,
    ) {
    }

    /**
     * @param AddressValidationIssueLegacyInterface[] $issues
     */
    public static function create(AddressStatus $status, ?AddressData $addressData, array $issues = [], ?GeoPoint $geoPoint = null, ?string $providerKey = null): self
    {
        return new self($status, $addressData, $issues, $geoPoint, $providerKey);
    }

    public function status(): AddressStatus
    {
        return $this->status;
    }

    public function addressData(): ?AddressData
    {
        return $this->addressData;
    }

    public function issues(): array
    {
        return $this->issues;
    }

    public function geoPoint(): ?GeoPoint
    {
        return $this->geoPoint;
    }

    public function providerKey(): ?string
    {
        return $this->providerKey;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status->value,
            'address' => $this->addressData?->toArray(),
            'issues' => array_map(static fn (AddressValidationIssueLegacyInterface $issue): array => [
                'field' => $issue->field(),
                'code' => $issue->code(),
                'message' => $issue->message(),
            ], $this->issues),
            'geoPoint' => $this->geoPoint ? ['latitude' => $this->geoPoint->latitude, 'longitude' => $this->geoPoint->longitude] : null,
            'providerKey' => $this->providerKey,
        ];
    }
}
