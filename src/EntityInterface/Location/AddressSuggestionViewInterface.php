<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface AddressSuggestionViewInterface
{
    public function label(): string;

    public function address(): AddressViewInterface;

    public function providerKey(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
