<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderKeyRotationLegacyInterface
{
    public function active(string $providerId, string $tenantId, string $region): string;

    public function rotate(string $providerId, string $tenantId, string $region): string;
}
