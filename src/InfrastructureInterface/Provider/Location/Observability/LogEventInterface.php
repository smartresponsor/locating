<?php

declare(strict_types=1);

namespace App\Locating\InfrastructureInterface\Provider\Location\Observability;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface LogEventInterface
{
    public function toJson(array $event, string $traceId = '', string $spanId = ''): string;
}
