<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Contract;

interface ProviderContractGoldenInterface
{
    public function record(string $providerId, string $op, array $request, array $response): void;

    public function verify(string $providerId, string $op, array $request, array $response, bool $update = false): bool;

    public function path(string $providerId, string $op, array $request): string;
}
