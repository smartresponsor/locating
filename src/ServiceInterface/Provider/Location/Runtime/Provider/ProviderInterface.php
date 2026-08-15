<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Provider;

interface ProviderInterface
{
    public function nameEntity(): string;

    /** @return array<string,mixed> */
    public function geocode(string $q): array;
}
