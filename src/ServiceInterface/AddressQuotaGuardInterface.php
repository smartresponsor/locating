<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\ServiceInterface;

interface AddressQuotaGuardInterface
{
    /**
     * Return true if the current tenant is allowed to perform the given operation.
     */
    public function isAllowed(string $operation): bool;
}
