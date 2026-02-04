<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\InfrastructureInterface\Locator;

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
