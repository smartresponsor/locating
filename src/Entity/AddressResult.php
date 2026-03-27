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

    public function componentValue(string $key): ?string
    {
        if ($this->addressData === null) {
            return null;
        }

        return $this->addressData->toArray()[$key] ?? null;
    }

    public function normalizedLine(): ?string
    {
        if ($this->addressData === null) {
            return null;
        }

        $parts = [
            $this->addressData->street(),
            $this->addressData->city(),
            $this->addressData->region(),
            $this->addressData->postalCode(),
            $this->addressData->countryCode(),
        ];

        $parts = array_values(array_filter($parts, static fn (string $part): bool => $part !== ''));

        return $parts === [] ? null : implode(', ', $parts);
    }
}
