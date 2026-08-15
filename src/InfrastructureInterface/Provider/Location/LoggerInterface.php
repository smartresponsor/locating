<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location;

interface LoggerInterface
{
    public function info(string $m, array $c = []): void;
    public function error(string $m, array $c = []): void;
}
