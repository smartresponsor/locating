<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Metrics\Correlation;

final class CorrelationId
{
    private static ?string $current = null;
    public static function generate(): string
    {
        return bin2hex(random_bytes(8));
    }
    public static function set(string $id): void
    {
        self::$current = $id;
    }
    public static function get(): ?string
    {
        return self::$current;
    }
}
