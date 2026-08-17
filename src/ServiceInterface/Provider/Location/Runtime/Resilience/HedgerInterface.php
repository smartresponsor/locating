<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface HedgerInterface
{
    /** @return list<int> */
    public function offsets(int $baseMs, int $p95Ms): array;
}
