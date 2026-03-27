<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Provider\Location;

interface FakeProviderLegacyInterface
{
    /** @return array<string,mixed> */
    public function call(array $request): array;
}
