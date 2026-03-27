<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface TracePropagatorLegacyInterface
{
    public function extract(array $header): array;

    public function inject(array $header, array $ctx): array;
}
