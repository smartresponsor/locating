<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface RegionRouterInterface
{
    public function select(array $regionHealth, array $slaWeight): string;
}
