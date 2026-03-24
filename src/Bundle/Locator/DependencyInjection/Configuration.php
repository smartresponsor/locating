<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Bundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('smart_responsor_locator');
        $root = $treeBuilder->getRootNode();

        $root
            ->children()
                ->arrayNode('providers')
                    ->scalarPrototype()->end()
                    ->requiresAtLeastOneElement()
                    ->defaultValue(['osm://?priority=100', 'fallback://?priority=0'])
                ->end()
                ->integerNode('retries')->min(0)->defaultValue(2)->end()
                ->integerNode('retry_base_ms')->min(1)->defaultValue(100)->end()
                ->integerNode('cb_threshold')->min(1)->defaultValue(5)->end()
                ->integerNode('cb_cooldown_sec')->min(1)->defaultValue(30)->end()
                ->integerNode('min_ttl')->min(1)->defaultValue(60)->end()
                ->integerNode('base_ttl')->min(1)->defaultValue(300)->end()
                ->integerNode('max_ttl')->min(1)->defaultValue(1800)->end()
                ->integerNode('neg_cache_ttl')->min(1)->defaultValue(60)->end()
                ->integerNode('cache_max')->min(1)->defaultValue(256)->end()
                ->integerNode('metrics_window')->min(1)->defaultValue(256)->end()
            ->end();

        return $treeBuilder;
    }
}
