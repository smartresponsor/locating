<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Location\Tenant;

interface TenantSeparatorInterface
{
    public function schema(string $tenantId): string;

    public function storageKey(string $tenantId, string $baseKey): string;
}
