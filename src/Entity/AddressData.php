<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Entity;

use App\EntityInterface\AddressDataInterface;

final class AddressData implements AddressDataInterface
{
    public function __construct(
        public string $street,
        public string $city,
        public string $region,
        public string $postalCode,
        public string $countryCode,
        public ?string $house = null,
        public ?string $unit = null
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string)($data['street'] ?? ''),
            (string)($data['city'] ?? ''),
            (string)($data['region'] ?? ''),
            (string)($data['postalCode'] ?? ''),
            (string)($data['countryCode'] ?? ''),
            isset($data['house']) ? (string)$data['house'] : null,
            isset($data['unit']) ? (string)$data['unit'] : null
        );
    }

    public function toArray(): array
    {
        return [
            'street' => $this->street(),
            'city' => $this->city(),
            'region' => $this->region(),
            'postalCode' => $this->postalCode(),
            'countryCode' => $this->countryCode(),
        ];
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

    public function house(): ?string
    {
        return $this->house;
    }

    public function unit(): ?string
    {
        return $this->unit;
    }

    /**
     * @return array<string, string>
     */
    public function toComponentMap(): array
    {
        return array_filter([
            'street' => $this->street(),
            'city' => $this->city(),
            'region' => $this->region(),
            'postalCode' => $this->postalCode(),
            'countryCode' => $this->countryCode(),
            'house' => $this->house ?? '',
            'unit' => $this->unit ?? '',
        ], static fn (string $value): bool => $value !== '');
    }
}
