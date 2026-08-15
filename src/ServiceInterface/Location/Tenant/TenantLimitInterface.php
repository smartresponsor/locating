<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Location\Tenant;

interface TenantLimitInterface
{
    public function tenantId(): string;

    public function operation(): string;

    public function limitPerMinute(): int;
}
