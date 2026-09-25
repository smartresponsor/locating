<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\PolicyInterface\Provider\Location;

interface AddressReverseSourceHealthPolicyInterface
{
    public function score(string $sourceKey): float;
}
