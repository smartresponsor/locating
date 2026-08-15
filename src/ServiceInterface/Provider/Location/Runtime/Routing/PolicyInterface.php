<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface PolicyInterface
{
    public function order(string $purpose, ?string $region): array;
}
