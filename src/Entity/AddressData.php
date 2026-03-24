<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Entity;

final class AddressData
{
    public function __construct(
        public string $street,
        public string $city,
        public string $region,
        public string $postalCode,
        public string $countryCode
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            (string)($data['street'] ?? ''),
            (string)($data['city'] ?? ''),
            (string)($data['region'] ?? ''),
            (string)($data['postalCode'] ?? ''),
            (string)($data['countryCode'] ?? '')
        );
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
