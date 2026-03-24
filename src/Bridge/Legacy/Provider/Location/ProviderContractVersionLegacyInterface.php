<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderContractVersionLegacyInterface
{
    public function register(string $providerId, string $op, string $version): void;

    public function select(string $providerId, string $op, ?string $want): string;

    public function support(string $providerId, string $op, string $version): bool;
}
