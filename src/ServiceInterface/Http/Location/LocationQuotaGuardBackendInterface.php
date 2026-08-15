<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ServiceInterface\Http\Location;

interface LocationQuotaGuardBackendInterface
{
    public function isAllowedSuggest(): bool;

    public function isAllowedReverse(): bool;
}
