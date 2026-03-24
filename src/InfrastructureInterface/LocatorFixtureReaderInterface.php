<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\InfrastructureInterface;

/**
 * Load demo fixture record list for Locator from a NDJSON file.
 */
interface LocatorFixtureReaderInterface
{
    /**
     * @return array<int,array<string,mixed>>
     */
    public function readFixture(string $filePath): array;
}
