<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Tests\Locator\Fixture;

use PHPUnit\Framework\TestCase;

final class AddressFixtureFileTest extends TestCase
{
    public function testGoldenFixtureFileIsReadableAndWellFormed(): void
    {
        $path = __DIR__ . '/address-golden.ndjson';

        self::assertFileExists($path, 'Golden fixture file must exist');

        $handle = fopen($path, 'rb');
        self::assertIsResource($handle);

        $lineCount = 0;

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $record = json_decode($line, true, 512, JSON_THROW_ON_ERROR);

            self::assertIsArray($record);
            self::assertArrayHasKey('id', $record);
            self::assertArrayHasKey('kind', $record);
            self::assertArrayHasKey('input', $record);

            $this->assertIsString($record['id']);
            $this->assertIsString($record['kind']);
            $this->assertIsArray($record['input']);

            $lineCount++;
        }

        fclose($handle);

        self::assertGreaterThanOrEqual(3, $lineCount, 'Expected at least a few golden records');
    }
}
