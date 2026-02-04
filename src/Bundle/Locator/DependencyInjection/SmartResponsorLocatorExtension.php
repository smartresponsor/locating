<?php
declare(strict_types=1);
namespace SmartResponsor\Bundle\Locator\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Definition;
use SmartResponsor\Integration\Locator\{LocatorConfig, LocatorSelector};
final class SmartResponsorLocatorExtension extends Extension {
  public function load(array $configs, ContainerBuilder $container) {
    $configuration = new Configuration();
    $cfg = $this->processConfiguration($configuration, $configs);
    $defCfg = new Definition(LocatorConfig::class, [
      $cfg['providers'], $cfg['retries'], $cfg['retry_base_ms'], $cfg['cb_threshold'],
      $cfg['cb_cooldown_sec'], $cfg['cache_ttl'], $cfg['neg_cache_ttl'], $cfg['cache_max']
    ]);
    $container->setDefinition('smart_responsor.locator.config', $defCfg);
    $defSel = new Definition(LocatorSelector::class);
    $defSel->setArgument(0, $defCfg);
    $container->setDefinition('smart_responsor.locator.selector', $defSel)->setPublic(true);
  }
}
