<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Credential;

interface ProviderKeyVaultInterface
{
    public function add(string $providerId, string $keyId, string $secret, int $priority, int $tsStart, int $tsEnd): void;

    public function current(string $providerId): ?string;

    public function rotate(string $providerId): ?string;
}
