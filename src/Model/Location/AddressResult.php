<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Model\Location;

use App\Locating\ModelInterface\Location\AddressIssueInterface;
use App\Locating\ModelInterface\Location\AddressReverseResultInterface;
use App\Locating\ModelInterface\Location\AddressViewInterface;

final class AddressResult implements AddressReverseResultInterface
{
    /**
     * @param list<AddressIssueInterface> $issues
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
     * @param list<AddressIssueInterface> $issues
     */
    public static function create(AddressStatus $status, ?AddressData $addressData, array $issues = [], ?GeoPoint $geoPoint = null, ?string $providerKey = null): self
    {
        return new self($status, $addressData, $issues, $geoPoint, $providerKey);
    }

    public function status(): string
    {
        return $this->status->value;
    }

    public function address(): ?AddressViewInterface
    {
        return null === $this->addressData ? null : AddressView::fromArray($this->addressData->toArray());
    }

    public function issues(): array
    {
        return array_map(static fn (AddressIssueInterface $issue): array => $issue->toArray(), $this->issues);
    }

    public function geoPoint(): ?array
    {
        return null === $this->geoPoint ? null : ['latitude' => $this->geoPoint->latitude, 'longitude' => $this->geoPoint->longitude];
    }

    public function providerKey(): ?string
    {
        return $this->providerKey;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status->value,
            'address' => $this->address()?->toArray(),
            'issues' => $this->issues(),
            'geoPoint' => $this->geoPoint(),
            'providerKey' => $this->providerKey,
        ];
    }
}
