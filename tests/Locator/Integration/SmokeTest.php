<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Locating\Tests\Locator\Integration;

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;

#[Group('smoke')]
final class SmokeTest extends TestCase
{
    public function testLocatorFixturesRunnerWorksAgainstLocalHarness(): void
    {
        $socket = stream_socket_server('tcp://127.0.0.1:0', $socketErrorNumber, $socketErrorMessage);
        self::assertIsResource($socket, $socketErrorMessage);
        $socketName = stream_socket_get_name($socket, false);
        self::assertIsString($socketName);
        fclose($socket);

        $separatorPosition = strrpos($socketName, ':');
        self::assertNotFalse($separatorPosition);
        $port = (int) substr($socketName, $separatorPosition + 1);
        self::assertGreaterThan(0, $port);

        $docRoot = realpath(__DIR__ . '/../../../public');
        self::assertNotFalse($docRoot);

        $serverCommand = sprintf(
            '%s -S 127.0.0.1:%d -t %s %s',
            escapeshellarg(PHP_BINARY),
            $port,
            escapeshellarg($docRoot),
            escapeshellarg($docRoot . '/index.php'),
        );

        $descriptorSpec = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];

        $serverProcess = proc_open($serverCommand, $descriptorSpec, $serverPipes);
        self::assertIsResource($serverProcess);
        fclose($serverPipes[0]);

        $baseUrl = sprintf('http://127.0.0.1:%d', $port);
        $ready = false;
        for ($attempt = 0; $attempt < 50; ++$attempt) {
            $body = @file_get_contents($baseUrl . '/');
            if (false !== $body) {
                $payload = json_decode($body, true);
                if (is_array($payload) && 'ok' === ($payload['status'] ?? null)) {
                    $ready = true;
                    break;
                }
            }
            usleep(50000);
        }
        self::assertTrue($ready, 'Locating smoke server did not become ready.');

        try {
            $runnerCommand = sprintf('%s %s', escapeshellarg(PHP_BINARY), escapeshellarg(__DIR__ . '/../../../tools/locator-fixtures-run.php'));
            $runnerDescriptorSpec = [
                0 => ['pipe', 'r'],
                1 => ['pipe', 'w'],
                2 => ['pipe', 'w'],
            ];

            $inheritedEnvironment = getenv();
            self::assertIsArray($inheritedEnvironment);
            $env = array_merge($inheritedEnvironment, [
                'LOCATOR_BASE_URL' => $baseUrl,
                'LOCATOR_TENANT' => 'smoke',
            ]);

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
            self::assertStringContainsString('/locator/address/reverse', $stdout);
            self::assertStringContainsString('HTTP/1.1 200 OK', $stdout);
        } finally {
            proc_terminate($serverProcess);
            foreach ([1, 2] as $pipeIndex) {
                if (isset($serverPipes[$pipeIndex]) && is_resource($serverPipes[$pipeIndex])) {
                    fclose($serverPipes[$pipeIndex]);
                }
            }
            proc_close($serverProcess);
        }
    }
}
