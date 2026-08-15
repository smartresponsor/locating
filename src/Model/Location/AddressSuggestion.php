<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Model\Location;

use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;
use App\Locating\ModelInterface\Location\AddressViewInterface;

final class AddressSuggestion implements AddressSuggestionResultInterface
{
    public function __construct(
        private string $label,
        private AddressData $addressData,
        private ?string $providerKey = null,
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

    public function address(): AddressViewInterface
    {
        return AddressView::fromArray($this->addressData->toArray());
    }

    public function providerKey(): ?string
    {
        return $this->providerKey;
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'address' => $this->address()->toArray(),
            'providerKey' => $this->providerKey,
        ];
    }
}
