<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Entity\Location;

use Smartresponsor\Entity\Locator\AddressData;

interface AddressSuggestionLegacyInterface
{
    public function label(): string;

    public function addressData(): AddressData;

    public function providerKey(): ?string;
}
