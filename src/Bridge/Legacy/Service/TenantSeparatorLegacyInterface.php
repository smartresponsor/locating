<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface TenantSeparatorLegacyInterface
{
    public function schema(string $tenantId): string;

    public function storageKey(string $tenantId, string $baseKey): string;
}
