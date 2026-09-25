<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\PolicyInterface\Provider\Location;

interface AddressSuggestionSourceHealthPolicyInterface
{
    public function score(string $sourceKey): float;
}
