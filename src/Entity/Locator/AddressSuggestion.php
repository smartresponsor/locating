<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Entity\Locator;

use App\EntityInterface\Locator\AddressSuggestionInterface;

final class AddressSuggestion implements AddressSuggestionInterface
{
    public function __construct(
        private string $label,
        private AddressData $addressData,
        private ?string $providerKey = null
    ) {
    }

    public function label(): string
    {
        return $this->label;
    }

    public function addressData(): AddressData
    {
        return $this->addressData;
    }

    public function providerKey(): ?string
    {
        return $this->providerKey;
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'address' => $this->addressData->toArray(),
            'providerKey' => $this->providerKey,
        ];
    }
}
