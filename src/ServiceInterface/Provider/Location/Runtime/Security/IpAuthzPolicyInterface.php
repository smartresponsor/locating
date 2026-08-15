<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Security;

interface IpAuthzPolicyInterface
{
    public function allow(string $tenantId, string $ip): bool;

    public function add(string $tenantId, string $action, string $cidr, int $priority): void;
}
