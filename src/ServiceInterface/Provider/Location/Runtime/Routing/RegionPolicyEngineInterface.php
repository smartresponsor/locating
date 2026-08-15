<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface RegionPolicyEngineInterface
{
    public function decide(array $hint): string;

    public function set(array $rule): void;
}
