<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\CommandInterface\Locator;

interface LocatorDemoSeedCommandInterface
{
    public function runSeed(string $tenantId, ?string $filePath = null): int;
}
