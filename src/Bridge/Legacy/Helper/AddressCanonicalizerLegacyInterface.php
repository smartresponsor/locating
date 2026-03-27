<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Helper\Location;

interface AddressCanonicalizerLegacyInterface
{
    /** @return array<string,mixed> */
    public function normalize(array $raw, string $locale = 'en'): array;
}
