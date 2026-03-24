<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderSandboxLegacyInterface
{
    public function register(string $providerId, ProviderAdapterLegacyInterface $adapter): void;

    /** @return array<string,mixed> */
    public function route(string $providerId, array $request): array;
}
