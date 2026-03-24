<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 */

namespace App\EntityInterface;

interface AddressDataInterface
{
    public function countryCode(): ?string;

    public function region(): ?string;

    public function city(): ?string;

    public function postalCode(): ?string;

    public function street(): ?string;

    public function house(): ?string;

    public function unit(): ?string;

    /**
     * @return array<string, string>
     */
    public function toComponentMap(): array;
}

