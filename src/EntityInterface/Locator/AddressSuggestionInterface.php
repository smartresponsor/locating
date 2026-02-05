<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\EntityInterface\Locator;

use Smartresponsor\Entity\Locator\AddressData;

interface AddressSuggestionInterface
{
    public function label(): string;

    public function addressData(): AddressData;

    public function providerKey(): ?string;
}
