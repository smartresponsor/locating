<?php

declare(strict_types=1);

namespace App\Locating\Tests\Support\Provider\Location\Interface;

interface ProviderAdapterInterface
{
    /** @param array<string,mixed> $request @return array<string,mixed> */
    public function call(array $request): array;
}
