<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Observability;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface LogEventInterface
{
    public function toJson(array $event, string $traceId = '', string $spanId = ''): string;
}
