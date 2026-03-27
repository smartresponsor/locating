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
        if (is_array($data)) {
            $this->data = $data;

            return;
        }

        $this->data = array_filter([
            'countryCode' => $data ?? $countryCode,
            'region' => $region,
            'city' => $city,
            'postalCode' => $postalCode,
            'street' => $street,
            'house' => $house,
            'unit' => $unit,
        ], static fn (mixed $value): bool => $value !== null && $value !== '');
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
        $value = $this->data['countryCode'] ?? null;

        return is_string($value) && $value !== '' ? strtoupper($value) : null;
    }
}
