<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface FailoverPlannerLegacyInterface
{
    public function plan(string $region, array $provider, array $signal): array;
}
