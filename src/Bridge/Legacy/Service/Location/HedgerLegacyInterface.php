<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface HedgerLegacyInterface
{
    public function offsets(int $baseMs, int $p95Ms): array;
}
