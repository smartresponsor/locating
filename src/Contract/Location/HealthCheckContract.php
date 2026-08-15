<?php

declare(strict_types=1);

namespace App\Locating\Contract\Location;

interface HealthCheckContract
{
    /** @return array{status:string,details?:array<string,mixed>} */
    public function check(): array;

    public function nameEntity(): string;
}
