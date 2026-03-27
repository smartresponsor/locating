<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface RegionPolicyEngineLegacyInterface
{
    public function decide(array $hint): string;

    public function set(array $rule): void;
}
