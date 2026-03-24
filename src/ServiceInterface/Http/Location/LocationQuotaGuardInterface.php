<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\ServiceInterface\Http\Location;

interface LocationQuotaGuardInterface
{
    public const OPERATION_REVERSE = 'reverse';
    public const OPERATION_SUGGEST = 'suggest';

    public function isAllowed(string $operation): bool;
}
