<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface SecretsProviderLegacyInterface
{
    public function get(string $key, ?string $default = null): ?string;
}
