<?php

declare(strict_types=1);

namespace App\Locating\Infrastructure\Provider\Location\Io;

final class JsonlReader
{
    /** @return \Generator<int,array<string,mixed>> */
    public static function read(string $p): \Generator
    {
        $f = fopen($p, 'r');
        if (false === $f) {
            throw new \RuntimeException('Unable to open JSONL file: '.$p);
        }
        try {
            while (($line = fgets($f)) !== false) {
                $decoded = json_decode($line, true);
                if (!is_array($decoded)) {
                    continue;
                }
                $row = [];
                foreach ($decoded as $key => $value) {
                    if (is_string($key)) {
                        $row[$key] = $value;
                    }
                }
                yield $row;
            }
        } finally {
            fclose($f);
        }
    }
}
