<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Location\Tenant;

interface TenantQuotaInterface
{
    public function limit(string $tenantId, string $operation): int;

    public function update(string $tenantId, string $operation, int $used, float $errorRate): int;

    public function setBase(string $tenantId, string $operation, int $base): void;
}
