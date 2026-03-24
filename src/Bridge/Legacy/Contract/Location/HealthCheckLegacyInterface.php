<?php

declare(strict_types=1);

namespace App\Bridge\Legacy\Contract\Location;

interface HealthCheckLegacyInterface
{
    /** @return array{status:string,details?:array<string,mixed>} */
    public function check(): array;

    public function name(): string;
}
