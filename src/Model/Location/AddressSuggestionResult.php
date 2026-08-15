<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Model\Location;

use App\Locating\ModelInterface\Location\AddressSuggestionResultInterface;
use App\Locating\ModelInterface\Location\AddressViewInterface;

final class AddressSuggestionResult implements AddressSuggestionResultInterface
{
    public function __construct(
        private readonly string $label,
        private readonly AddressViewInterface $address,
        private readonly ?string $providerKey,
    ) {
    }

    public function label(): string
    {
        return $this->label;
    }

    public function address(): AddressViewInterface
    {
        return $this->address;
    }

    public function providerKey(): ?string
    {
        return $this->providerKey;
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'address' => $this->address->toArray(),
            'providerKey' => $this->providerKey,
        ];
    }
}
