<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface RegionRouterInterface
{
    /**
     * @param array<string,float|int> $regionHealth
     * @param array<string,float|int> $slaWeight
     */
    public function select(array $regionHealth, array $slaWeight): string;
}
