<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

interface ProviderQuotaDecisionBackendInterface
{
    public function allow(string $tenantId, string $operation, int $units = 1, bool $record = false): bool;
}
