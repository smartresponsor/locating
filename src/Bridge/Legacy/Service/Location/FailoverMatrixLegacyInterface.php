<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface FailoverMatrixLegacyInterface
{
    public function candidate(string $region, string $primary): array;
}
