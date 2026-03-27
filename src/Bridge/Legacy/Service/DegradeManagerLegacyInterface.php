<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Service\Location;

interface DegradeManagerLegacyInterface
{
    public function decide(string $op, float $health, float $errorRate, bool $hasCache): string;
}
