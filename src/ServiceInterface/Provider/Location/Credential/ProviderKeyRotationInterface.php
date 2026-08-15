<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Credential;

interface ProviderKeyRotationInterface
{
    public function active(string $providerId, string $tenantId, string $region): string;

    public function rotate(string $providerId, string $tenantId, string $region): string;
}
