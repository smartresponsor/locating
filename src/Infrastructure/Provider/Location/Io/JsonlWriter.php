<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location\Io;

final class JsonlWriter
{
    /** @param array<string,mixed> $r */
    public static function append(string $p, array $r): void
    {
        file_put_contents($p, json_encode($r, JSON_THROW_ON_ERROR)."\n", FILE_APPEND);
    }
}
