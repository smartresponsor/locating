<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\ServiceInterface;

interface LocatorDemoSeedInterface
{
    public function seedFromFile(string $tenantId, string $filePath): int;

    public function seedDemo(string $tenantId): int;
}
