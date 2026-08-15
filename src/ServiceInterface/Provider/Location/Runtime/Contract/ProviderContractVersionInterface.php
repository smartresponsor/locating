<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Contract;

interface ProviderContractVersionInterface
{
    public function register(string $providerId, string $op, string $version): void;

    public function select(string $providerId, string $op, ?string $want): string;

    public function support(string $providerId, string $op, string $version): bool;
}
