<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Tests\Provider;

use Smartresponsor\Entity\AddressInput;
use Smartresponsor\Entity\HealthRecorder;
use Smartresponsor\Entity\ProviderSandbox;
use Smartresponsor\Infrastructure\InMemoryMetricRecorder;
use Smartresponsor\InfrastructureInterface\ProviderAdapterInterface;
use Smartresponsor\Service\FailoverPlanner;
use Smartresponsor\Service\ProviderRouter;
use Smartresponsor\Service\SlaPolicy;
use PHPUnit\Framework\TestCase;

final class ProviderRouterTest extends TestCase
{
    public function testRoutesToFirstHealthyProvider(): void
    {
        $sandbox = new ProviderSandbox();
        $sandbox->register('primary', new class() implements ProviderAdapterInterface {
            public function call(array $request): array
            {
                return ['status' => 'ok', 'provider' => 'primary', 'q' => $request['q'] ?? ''];
            }
        });
        $sandbox->register('secondary', new class() implements ProviderAdapterInterface {
            public function call(array $request): array
            {
                return ['status' => 'ok', 'provider' => 'secondary', 'q' => $request['q'] ?? ''];
            }
        });

        $router = new ProviderRouter(
            $sandbox,
            new FailoverPlanner(new SlaPolicy()),
            new HealthRecorder(),
            new InMemoryMetricRecorder()
        );

        $input = new AddressInput('Houston, TX');
        $result = $router->route($input, 'us', 'tenant-1', ['primary', 'secondary']);

        self::assertSame('ok', $result['status'] ?? null);
        self::assertSame('primary', $result['_provider'] ?? $result['provider'] ?? null);
        self::assertArrayHasKey('latencyMs', $result);
    }

    public function testFailsOverOnError(): void
    {
        $sandbox = new ProviderSandbox();
        $sandbox->register('bad', new class() implements ProviderAdapterInterface {
            public function call(array $request): array
            {
                return ['status' => 'error', 'error' => 'forced'];
            }
        });
        $sandbox->register('good', new class() implements ProviderAdapterInterface {
            public function call(array $request): array
            {
                return ['status' => 'ok', 'provider' => 'good', 'q' => $request['q'] ?? ''];
            }
        });

        $router = new ProviderRouter(
            $sandbox,
            new FailoverPlanner(new SlaPolicy()),
            new HealthRecorder(),
            new InMemoryMetricRecorder()
        );

        $input = new AddressInput('Houston, TX');
        $result = $router->route($input, 'us', 'tenant-1', ['bad', 'good']);

        self::assertSame('ok', $result['status'] ?? null);
        self::assertSame('good', $result['_provider'] ?? $result['provider'] ?? null);
    }

    public function testAllProvidersFail(): void
    {
        $sandbox = new ProviderSandbox();
        $sandbox->register('bad1', new class() implements ProviderAdapterInterface {
            public function call(array $request): array
            {
                return ['status' => 'error', 'error' => 'forced1'];
            }
        });
        $sandbox->register('bad2', new class() implements ProviderAdapterInterface {
            public function call(array $request): array
            {
                return ['status' => 'error', 'error' => 'forced2'];
            }
        });

        $router = new ProviderRouter(
            $sandbox,
            new FailoverPlanner(new SlaPolicy()),
            new HealthRecorder(),
            new InMemoryMetricRecorder()
        );

        $input = new AddressInput('Houston, TX');
        $result = $router->route($input, 'us', 'tenant-1', ['bad1', 'bad2']);

        self::assertSame('error', $result['status'] ?? null);
        self::assertNull($result['_provider']);
        self::assertArrayHasKey('providerList', $result);
    }
}
