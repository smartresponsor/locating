<?php

declare(strict_types=1);

namespace App\Locating\SerializerInterface\Observability\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface LogEventSerializerInterface
{
    /** @param array<string,mixed> $event */
    public function toJson(array $event, string $traceId = '', string $spanId = ''): string;
}
