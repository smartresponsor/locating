<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderKeyVaultLegacyInterface
{
    public function add(string $providerId, string $keyId, string $secret, int $priority, int $tsStart, int $tsEnd): void;

    public function current(string $providerId): ?string;

    public function rotate(string $providerId): ?string;
}
