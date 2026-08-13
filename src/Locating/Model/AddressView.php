<?php

declare(strict_types=1);

namespace App\Locating\Model;

final readonly class AddressView
{
    public function __construct(
        private string $street,
        private string $city,
        private string $region,
        private string $postalCode,
        private string $countryCode,
    ) {
    }

    /** @param array<string, mixed> $payload */
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

    public function street(): string { return $this->street; }
    public function city(): string { return $this->city; }
    public function region(): string { return $this->region; }
    public function postalCode(): string { return $this->postalCode; }
    public function countryCode(): string { return $this->countryCode; }

    /** @return array{street:string,city:string,region:string,postalCode:string,countryCode:string} */
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
