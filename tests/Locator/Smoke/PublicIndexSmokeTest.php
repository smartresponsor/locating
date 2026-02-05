<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Tests\Locator\Smoke;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('smoke')]
final class PublicIndexSmokeTest extends TestCase
{
    public function testPublicIndexReturnsJsonContract(): void
    {
        $command = sprintf('%s %s', escapeshellarg(PHP_BINARY), escapeshellarg(__DIR__ . '/../../../public/index.php'));
        $output = shell_exec($command);

        self::assertNotNull($output);

        $payload = json_decode($output, true, 512, JSON_THROW_ON_ERROR);

        self::assertSame('ok', $payload['status'] ?? null);
        self::assertSame('locator-sketch30', $payload['component'] ?? null);
        self::assertArrayHasKey('time', $payload);
    }
}
