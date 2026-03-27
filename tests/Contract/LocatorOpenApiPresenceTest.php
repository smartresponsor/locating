<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Tests\Contract;

use PHPUnit\Framework\TestCase;

final class LocatorOpenApiPresenceTest extends TestCase
{
    public function testOpenApiFileExistsAndContainsCorePaths(): void
    {
        $path = dirname(__DIR__, 2) . '/openapi/locator-v1.yaml';

        self::assertFileExists($path, 'OpenAPI contract file must exist');

        $content = (string)file_get_contents($path);
        self::assertNotSame('', $content, 'OpenAPI contract file must not be empty');

        $this->assertStringContainsString('/locator/status:', $content);
        $this->assertStringContainsString('/locator/address/suggest:', $content);
        $this->assertStringContainsString('/locator/address/reverse:', $content);
    }
}
