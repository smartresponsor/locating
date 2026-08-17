<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Routing;

interface RegionPolicyEngineInterface
{
    /** @param array<string, mixed> $hint */
    public function decide(array $hint): string;

    /** @param array<string, mixed> $rule */
    public function set(array $rule): void;
}
