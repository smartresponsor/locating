<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface FailoverMatrixInterface
{
    /** @return list<string> */
    public function candidate(string $region, string $primary): array;
}
