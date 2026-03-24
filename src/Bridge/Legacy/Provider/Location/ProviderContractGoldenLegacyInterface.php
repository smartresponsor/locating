<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface ProviderContractGoldenLegacyInterface
{
    public function record(string $providerId, string $op, array $request, array $response): void;

    public function verify(string $providerId, string $op, array $request, array $response, bool $update = false): bool;

    public function path(string $providerId, string $op, array $request): string;
}
