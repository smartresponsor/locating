<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Entity\Location;

interface AddressDataLegacyInterface
{
    public function countryCode(): ?string;

    public function region(): ?string;

    public function city(): ?string;

    public function postalCode(): ?string;

    public function street(): ?string;

    public function house(): ?string;

    public function unit(): ?string;

    /** @return array<string,string> */
    public function toComponentMap(): array;
}
