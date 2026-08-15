<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Model\Location;

use App\Locating\ModelInterface\Location\AddressViewInterface;

final class AddressView implements AddressViewInterface
{
    public function __construct(
        private readonly string $street,
        private readonly string $city,
        private readonly string $region,
        private readonly string $postalCode,
        private readonly string $countryCode,
    ) {
    }

    /**
     * @param array<string, mixed> $payload
     */
    public static function fromArray(array $payload): self
    {
        return new self(
            (string) ($payload['street'] ?? ''),
            (string) ($payload['city'] ?? ''),
            (string) ($payload['region'] ?? ''),
            (string) ($payload['postalCode'] ?? ''),
            (string) ($payload['countryCode'] ?? ''),
        );
    }

    public function street(): string
    {
        return $this->street;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function region(): string
    {
        return $this->region;
    }

    public function postalCode(): string
    {
        return $this->postalCode;
    }

    public function countryCode(): string
    {
        return $this->countryCode;
    }

    public function toArray(): array
    {
        return [
            'street' => $this->street,
            'city' => $this->city,
            'region' => $this->region,
            'postalCode' => $this->postalCode,
            'countryCode' => $this->countryCode,
        ];
    }
}
