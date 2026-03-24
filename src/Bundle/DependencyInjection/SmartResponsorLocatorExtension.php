<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Bundle\DependencyInjection;

use Smartresponsor\Integration\LocatorConfig;
use Smartresponsor\Integration\LocatorSelector;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

final class SmartresponsorLocatorExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $cfg = $this->processConfiguration($configuration, $configs);

        $container->setDefinition(
            'smart_responsor.locator.config',
            new Definition(LocatorConfig::class, [
                $cfg['providers'],
                $cfg['retries'],
                $cfg['retry_base_ms'],
                $cfg['cb_threshold'],
                $cfg['cb_cooldown_sec'],
                $cfg['min_ttl'],
                $cfg['base_ttl'],
                $cfg['max_ttl'],
                $cfg['neg_cache_ttl'],
                $cfg['cache_max'],
                $cfg['metrics_window'],
            ])
        );

        $container->setDefinition(
            'smart_responsor.locator.selector',
            (new Definition(LocatorSelector::class))
                ->setArgument('$cfg', new Reference('smart_responsor.locator.config'))
                ->setPublic(true)
        );
    }
}
