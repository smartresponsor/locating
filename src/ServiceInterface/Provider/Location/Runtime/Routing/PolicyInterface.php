<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface PolicyInterface
{
    /** @return list<string> */
    public function order(string $purpose, ?string $region): array;
}
