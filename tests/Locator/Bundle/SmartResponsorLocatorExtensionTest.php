<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Tests\Locator\Bundle;

use Smartresponsor\Bundle\Locator\DependencyInjection\SmartresponsorLocatorExtension;
use Smartresponsor\Integration\Locator\LocatorConfig;
use Smartresponsor\Integration\Locator\LocatorSelector;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SmartresponsorLocatorExtensionTest extends TestCase
{
    public function testExtensionRegistersConfigAndSelectorUsingValidatedConfigTree(): void
    {
        $container = new ContainerBuilder();
        $extension = new SmartresponsorLocatorExtension();

        $extension->load([
            [
                'providers' => ['osm://?priority=100', 'fallback://?priority=0'],
                'retries' => 3,
                'retry_base_ms' => 150,
                'cb_threshold' => 7,
                'cb_cooldown_sec' => 45,
                'min_ttl' => 30,
                'base_ttl' => 240,
                'max_ttl' => 1200,
                'neg_cache_ttl' => 50,
                'cache_max' => 300,
                'metrics_window' => 512,
            ],
        ], $container);

        self::assertTrue($container->hasDefinition('smart_responsor.locator.config'));
        self::assertTrue($container->hasDefinition('smart_responsor.locator.selector'));

        $configDefinition = $container->getDefinition('smart_responsor.locator.config');
        self::assertSame(LocatorConfig::class, $configDefinition->getClass());

        $selectorDefinition = $container->getDefinition('smart_responsor.locator.selector');
        self::assertSame(LocatorSelector::class, $selectorDefinition->getClass());
        self::assertTrue($selectorDefinition->isPublic());

        $arguments = $configDefinition->getArguments();
        self::assertSame(3, $arguments[1]);
        self::assertSame(150, $arguments[2]);
        self::assertSame(7, $arguments[3]);
        self::assertSame(30, $arguments[5]);
        self::assertSame(240, $arguments[6]);
        self::assertSame(1200, $arguments[7]);
        self::assertSame(512, $arguments[10]);
    }
}
