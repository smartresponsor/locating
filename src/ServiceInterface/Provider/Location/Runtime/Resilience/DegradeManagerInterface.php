<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience;

interface DegradeManagerInterface
{
    public function decide(string $op, float $health, float $errorRate, bool $hasCache): string;
}
