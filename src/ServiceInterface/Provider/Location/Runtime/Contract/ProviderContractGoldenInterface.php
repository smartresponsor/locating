<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Contract;

interface ProviderContractGoldenInterface
{
    /**
     * @param array<string, mixed> $request
     * @param array<string, mixed> $response
     */
    public function record(string $providerId, string $op, array $request, array $response): void;

    /**
     * @param array<string, mixed> $request
     * @param array<string, mixed> $response
     */
    public function verify(string $providerId, string $op, array $request, array $response, bool $update = false): bool;

    /** @param array<string, mixed> $request */
    public function path(string $providerId, string $op, array $request): string;
}
