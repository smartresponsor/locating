<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Entity;

use App\EntityInterface\AddressInputInterface;

final class AddressInput implements AddressInputInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $data;

    public function __construct(
        private string $rawLine,
        array|string|null $data = [],
        ?string $countryCode = null,
        ?string $region = null,
        ?string $city = null,
        ?string $postalCode = null,
        ?string $street = null,
        ?string $house = null,
        ?string $unit = null
    ) {
        $this->data = is_array($data)
            ? $data
            : self::buildStructuredData($data ?? $countryCode, $region, $city, $postalCode, $street, $house, $unit);
    }

    public static function fromArray(array $payload): self
    {
        $raw = (string)($payload['raw'] ?? '');
        $data = (array)($payload['data'] ?? []);

        return new self($raw, $data);
    }

    public function rawLine(): string
    {
        return $this->rawLine;
    }

    public function toArray(): array
    {
        return [
            'raw' => $this->rawLine,
            'data' => $this->data,
        ];
    }

    public function data(): array
    {
        return $this->data;
    }

    public function countryCode(): ?string
    {
        return self::normalizeCountryCode($this->data['countryCode'] ?? null);
    }

    /**
     * @return array<string, string>
     */
    private static function buildStructuredData(
        ?string $countryCode,
        ?string $region,
        ?string $city,
        ?string $postalCode,
        ?string $street,
        ?string $house,
        ?string $unit
    ): array {
        $values = [
            'countryCode' => self::normalizeCountryCode($countryCode),
            'region' => $region,
            'city' => $city,
            'postalCode' => $postalCode,
            'street' => $street,
            'house' => $house,
            'unit' => $unit,
        ];

        return array_filter(
            $values,
            static fn (?string $value): bool => $value !== null && $value !== ''
        );
    }

    private static function normalizeCountryCode(mixed $value): ?string
    {
        if (!is_string($value) || $value === '') {
            return null;
        }

        return strtoupper($value);
    }
}
