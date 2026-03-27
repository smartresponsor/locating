<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Tests\Integration;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('smoke')]
final class SmokeTest extends TestCase
{
    public function testLocatorFixturesRunnerWorksAgainstLocalHarness(): void
    {
        $port = $this->reserveFreePort();
        $projectRoot = dirname(__DIR__, 2);

        $serverCommand = sprintf(
            '%s -S 127.0.0.1:%d %s',
            escapeshellarg(PHP_BINARY),
            $port,
            escapeshellarg($projectRoot . '/router.php'),
        );

        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['file', 'php://temp', 'w+'],
            2 => ['file', 'php://temp', 'w+'],
        ];

        $serverProcess = proc_open($serverCommand, $descriptorSpec, $serverPipes, $projectRoot);
        self::assertIsResource($serverProcess);

        usleep(250000);

        try {
            $runnerCommand = sprintf('%s %s', escapeshellarg(PHP_BINARY), escapeshellarg(dirname(__DIR__, 2) . '/tools/locator-fixtures-run.php'));
            $runnerDescriptorSpec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $env = [
                'LOCATOR_BASE_URL' => sprintf('http://127.0.0.1:%d', $port),
                'LOCATOR_TENANT' => 'smoke',
            ];

            $runnerProcess = proc_open($runnerCommand, $runnerDescriptorSpec, $runnerPipes, null, $env);
            self::assertIsResource($runnerProcess);

            fclose($runnerPipes[0]);
            $stdout = stream_get_contents($runnerPipes[1]);
            $stderr = stream_get_contents($runnerPipes[2]);
            fclose($runnerPipes[1]);
            fclose($runnerPipes[2]);

            $runnerExitCode = proc_close($runnerProcess);

            self::assertSame('', trim($stderr));
            self::assertSame(0, $runnerExitCode);
            self::assertStringContainsString('Locator golden fixture run', $stdout);
            self::assertStringContainsString('/locator/address/suggest', $stdout);
            self::assertStringContainsString('HTTP/1.1 200 OK', $stdout);
        } finally {
            proc_terminate($serverProcess);
            proc_close($serverProcess);
        }
    }

    private function reserveFreePort(): int
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $errno, $errorMessage);
        self::assertNotFalse($socket, $errorMessage);

        $address = stream_socket_get_name($socket, false);
        fclose($socket);

        self::assertIsString($address);
        $parts = explode(':', $address);

        return (int) end($parts);
    }
}
