<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Entity\Location;

interface AddressInputLegacyInterface
{
    public function rawLine(): string;

    /** @return array<string,mixed> */
    public function toArray(): array;
}
