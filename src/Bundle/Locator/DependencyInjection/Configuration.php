<?php
declare(strict_types=1);
namespace Smartresponsor\Bundle\Locator\DependencyInjection;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
final class Configuration implements ConfigurationInterface {
  public function getConfigTreeBuilder(): TreeBuilder {
    $tb = new TreeBuilder('smart_responsor_locator');
    $root = $tb->getRootNode();
    $root
      ->children()
        ->arrayNode('providers')->scalarPrototype()->end()->defaultValue(['fallback://?priority=0'])->end()
        ->integerNode('retries')->defaultValue(2)->end()
        ->integerNode('retry_base_ms')->defaultValue(100)->end()
        ->integerNode('cb_threshold')->defaultValue(5)->end()
        ->integerNode('cb_cooldown_sec')->defaultValue(30)->end()
        ->integerNode('cache_ttl')->defaultValue(600)->end()
        ->integerNode('neg_cache_ttl')->defaultValue(60)->end()
        ->integerNode('cache_max')->defaultValue(256)->end()
      ->end();
    return $tb;
  }
}
