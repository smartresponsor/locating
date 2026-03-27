<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface TenantQuotaLegacyInterface
{
    public function limit(string $tenantId, string $op): int;

    public function update(string $tenantId, string $op, int $used, float $errorRate): int;

    public function setBase(string $tenantId, string $op, int $base): void;
}
