<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface IpAuthzPolicyLegacyInterface
{
    public function allow(string $tenantId, string $ip): bool;

    public function add(string $tenantId, string $action, string $cidr, int $priority): void;
}
