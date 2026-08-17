<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location;

interface LoggerInterface
{
    /** @param array<string,mixed> $c */
    public function info(string $m, array $c = []): void;

    /** @param array<string,mixed> $c */
    public function error(string $m, array $c = []): void;
}
