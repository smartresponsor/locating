<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\ServiceInterface\Locator;

interface LocatorDemoSeedInterface
{
    public function seedFromFile(string $tenantId, string $filePath): int;

    public function seedDemo(string $tenantId): int;
}
