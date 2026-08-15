<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location;

interface ProviderOrderInterface
{
    public function rank(array $signal, object $bandit): array;
}
