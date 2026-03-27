<?php

declare(strict_types=1);

namespace Tests\Security;

use PHPUnit\Framework\TestCase;

final class ApiKeyAuthTest extends TestCase
{
    public function testAllowsRequestWhenApiKeyIsNotConfigured(): void
    {
        self::assertSame('ok', $this->runRunner('-', '-'));
    }

    public function testRejectsMismatchedApiKey(): void
    {
        self::assertSame('{"error":"unauthorized"}', $this->runRunner('expected-secret', 'wrong-secret'));
    }

    public function testAllowsMatchingApiKey(): void
    {
        self::assertSame('ok', $this->runRunner('expected-secret', 'expected-secret'));
    }

    private function runRunner(string $apiKey, string $header): string
    {
        $script = dirname(__DIR__).'/fixtures/security/ApiKeyAuthRunner.php';
        $command = sprintf(
            '%s %s %s %s',
            escapeshellarg(PHP_BINARY),
            escapeshellarg($script),
            escapeshellarg($apiKey),
            escapeshellarg($header),
        );

        $output = shell_exec($command);
        self::assertNotFalse($output, 'Failed to execute ApiKeyAuth runner.');

        return trim((string) $output);
    }
}
