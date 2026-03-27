<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Provider\Location;

interface AddressSuggestionSourceHealthPolicyInterface
{
    public function score(string $sourceKey): float;
}
