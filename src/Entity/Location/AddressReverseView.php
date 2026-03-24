<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\AddressReverseViewInterface;
use App\EntityInterface\Location\AddressViewInterface;

final class AddressReverseView implements AddressReverseViewInterface
{
    /**
     * @param list<array{field:string, code:string, message:string}> $issues
     * @param array{latitude: float, longitude: float}|null          $geoPoint
     */
    public function __construct(
        private readonly string $status,
        private readonly ?AddressViewInterface $address,
        private readonly array $issues,
        private readonly ?array $geoPoint,
        private readonly ?string $providerKey,
    ) {
    }

    public function status(): string
    {
        return $this->status;
    }

    public function address(): ?AddressViewInterface
    {
        return $this->address;
    }

    public function issues(): array
    {
        return $this->issues;
    }

    public function geoPoint(): ?array
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
            'status' => $this->status,
            'address' => $this->address?->toArray(),
            'issues' => $this->issues,
            'geoPoint' => $this->geoPoint,
            'providerKey' => $this->providerKey,
        ];
    }
}
