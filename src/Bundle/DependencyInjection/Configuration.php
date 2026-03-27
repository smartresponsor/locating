<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('smartresponsor_locator');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->arrayNode('providers')
                    ->scalarPrototype()->end()
                    ->defaultValue(['osm://?priority=100'])
                ->end()
                ->integerNode('retries')->defaultValue(2)->end()
                ->integerNode('retry_base_ms')->defaultValue(100)->end()
                ->integerNode('cb_threshold')->defaultValue(5)->end()
                ->integerNode('cb_cooldown_sec')->defaultValue(30)->end()
                ->integerNode('min_ttl')->defaultValue(30)->end()
                ->integerNode('base_ttl')->defaultValue(300)->end()
                ->integerNode('max_ttl')->defaultValue(3600)->end()
                ->integerNode('neg_cache_ttl')->defaultValue(60)->end()
                ->integerNode('cache_max')->defaultValue(1000)->end()
                ->integerNode('metrics_window')->defaultValue(256)->end()
                ->scalarNode('redis_dsn')->defaultValue('%env(LOCATOR_REDIS_DSN)%')->end()
                ->integerNode('rate_per_minute')->defaultValue(60)->end()
                ->scalarNode('nominatim_base')->defaultValue('%env(NOMINATIM_BASE_URL)%')->end()
                ->scalarNode('nominatim_email')->defaultValue('%env(NOMINATIM_EMAIL)%')->end()
            ->end();

        return $treeBuilder;
    }
}
