<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface AdaptiveProviderOrderInterface
{
    public function rank(array $signal): array;
}
