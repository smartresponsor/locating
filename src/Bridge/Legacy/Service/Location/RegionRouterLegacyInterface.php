<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface RegionRouterLegacyInterface
{
    public function select(array $regionHealth, array $slaWeight): string;
}
