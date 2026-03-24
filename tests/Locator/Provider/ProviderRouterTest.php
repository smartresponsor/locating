<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Tests\Locator\Provider;

use App\Bridge\Legacy\Provider\Location\ProviderAdapterLegacyInterface;
use PHPUnit\Framework\TestCase;
use Smartresponsor\Entity\Locator\AddressInput;
use Smartresponsor\Entity\Locator\HealthRecorder;
use Smartresponsor\Entity\Locator\ProviderSandbox;
use Smartresponsor\Infrastructure\Locator\InMemoryMetricRecorder;
use Smartresponsor\Service\Locator\FailoverPlanner;
use Smartresponsor\Service\Locator\ProviderRouter;
use Smartresponsor\Service\Locator\SlaPolicy;

final class ProviderRouterTest extends TestCase
{
    public function testRoutesToFirstHealthyProvider(): void
    {
        $sandbox = new ProviderSandbox();
        $sandbox->register('primary', new class implements ProviderAdapterLegacyInterface {
            public function call(array $request): array
            {
                return ['status' => 'ok', 'provider' => 'primary', 'q' => $request['q'] ?? ''];
            }
        });
        $sandbox->register('secondary', new class implements ProviderAdapterLegacyInterface {
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
        $sandbox->register('bad', new class implements ProviderAdapterLegacyInterface {
            public function call(array $request): array
            {
                return ['status' => 'error', 'error' => 'forced'];
            }
        });
        $sandbox->register('good', new class implements ProviderAdapterLegacyInterface {
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
        $sandbox->register('bad1', new class implements ProviderAdapterLegacyInterface {
            public function call(array $request): array
            {
                return ['status' => 'error', 'error' => 'forced1'];
            }
        });
        $sandbox->register('bad2', new class implements ProviderAdapterLegacyInterface {
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
