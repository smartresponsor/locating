<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\ServiceInterface\Locator;

interface AddressQuotaGuardInterface
{
    /**
     * Return true if the current tenant is allowed to perform the given operation.
     */
    public function isAllowed(string $operation): bool;
}
