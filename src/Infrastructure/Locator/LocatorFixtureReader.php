<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Infrastructure\Locator;

use App\Bridge\Legacy\Infrastructure\Location\LocatorFixtureReaderInterface;

final class LocatorFixtureReader implements LocatorFixtureReaderInterface
{
    /**
     * @return array<int,array<string,mixed>>
     */
    public function readFixture(string $filePath): array
    {
        if (!is_file($filePath)) {
            throw new \RuntimeException(sprintf('Locator demo fixture file not found: %s', $filePath));
        }

        $recordList = [];
        $handle = fopen($filePath, 'r');

        if (false === $handle) {
            throw new \RuntimeException(sprintf('Unable to open Locator demo fixture file: %s', $filePath));
        }

        try {
            while (($line = fgets($handle)) !== false) {
                $line = trim($line);
                if ('' === $line) {
                    continue;
                }

                $decoded = json_decode($line, true, 512, JSON_THROW_ON_ERROR);
                if (!is_array($decoded)) {
                    continue;
                }

                if (!isset($decoded['raw']) || !is_string($decoded['raw'])) {
                    continue;
                }

                $recordList[] = $decoded;
            }
        } finally {
            fclose($handle);
        }

        return $recordList;
    }
}
