<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Address\Location;

interface AddressCanonicalizerInterface
{
    /** @return array<string,mixed> */
    public function normalize(array $raw, string $locale = 'en'): array;
}
