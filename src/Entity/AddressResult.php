<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\Entity;

use App\EntityInterface\AddressResultInterface;
use App\EntityInterface\AddressValidationIssueInterface;

/**
 * Immutable result of address validation and optional geocoding.
 */
final class AddressResult implements AddressResultInterface
{
    /**
     * @param AddressValidationIssueInterface[] $issues
     */
    public function __construct(
        private AddressStatus $status,
        private ?AddressData $addressData,
        private array $issues,
        private ?GeoPoint $geoPoint,
        private ?string $providerKey
    ) {
    }

    /**
     * @param AddressValidationIssueInterface[] $issues
     */
    public static function create(
        AddressStatus $status,
        ?AddressData $addressData,
        array $issues = [],
        ?GeoPoint $geoPoint = null,
        ?string $providerKey = null
    ): self {
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
            'issues' => array_map(
                static fn (AddressValidationIssueInterface $issue): array => [
                    'field' => $issue->field(),
                    'code' => $issue->code(),
                    'message' => $issue->message(),
                ],
                $this->issues
            ),
            'geoPoint' => $this->geoPoint
                ? [
                    'latitude' => $this->geoPoint->latitude,
                    'longitude' => $this->geoPoint->longitude,
                ]
                : null,
            'providerKey' => $this->providerKey,
        ];
    }
}
