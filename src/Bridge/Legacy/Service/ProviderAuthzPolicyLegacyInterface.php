<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface ProviderAuthzPolicyLegacyInterface
{
    public function allow(string $tenantId, string $region, string $op, string $providerId): bool;

    public function add(string $tenantId, string $region, string $op, string $providerId, string $action, int $priority): void;
}
