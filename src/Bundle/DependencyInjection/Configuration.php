<?php
declare(strict_types=1);
namespace Smartresponsor\Bundle\DependencyInjection;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;
final class Configuration implements ConfigurationInterface{
  public function getConfigTreeBuilder(): TreeBuilder{
    $tb = new TreeBuilder('smartresponsor_locator');
    $root = $tb->getRootNode();
    $root
      ->children()
        ->scalarNode('redis_dsn')->defaultValue('%env(LOCATOR_REDIS_DSN)%')->end()
        ->integerNode('rate_per_minute')->defaultValue(60)->end()
        ->scalarNode('nominatim_base')->defaultValue('%env(NOMINATIM_BASE_URL)%')->end()
        ->scalarNode('nominatim_email')->defaultValue('%env(NOMINATIM_EMAIL)%')->end()
      ->end();
    return $tb;
  }
}
