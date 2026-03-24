<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\CommandInterface;

interface LocatorDemoSeedCommandInterface
{
    public function runSeed(string $tenantId, ?string $filePath = null): int;
}
