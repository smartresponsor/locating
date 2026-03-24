<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Bundle\DependencyInjection;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Smartresponsor\Integration\Factory\RedisFactory;
use Smartresponsor\Integration\Cache\RedisCache;
use Smartresponsor\Integration\RateLimit\RedisRateLimiter;
use Smartresponsor\Integration\Http\NominatimClient;
use Smartresponsor\Strategy\OpenStreetMapLocator;
use Smartresponsor\Service\LocationLocatorService;
final class LocatorExtension extends Extension{
  public function load(array $configs, ContainerBuilder $container): void{
    $configuration = new Configuration();
    $config = $this->processConfiguration($configuration, $configs);

    $container->register('smartresponsor.redis', \Redis::class)
      ->setFactory([RedisFactory::class, 'createFromDsn'])
      ->addArgument($config['redis_dsn']);

    $container->register('smartresponsor.cache', RedisCache::class)
      ->addArgument(new Reference('smartresponsor.redis'));

    $container->register('smartresponsor.rate_limiter', RedisRateLimiter::class)
      ->addArgument(new Reference('smartresponsor.redis'));

    $container->register('smartresponsor.nominatim', NominatimClient::class)
      ->addArgument($config['nominatim_base'])
      ->addArgument($config['nominatim_email'])
      ->addArgument(10);

    $container->register('smartresponsor.locator_impl', OpenStreetMapLocator::class)
      ->addArgument(new Reference('smartresponsor.nominatim'));

    $container->register('smartresponsor.locator', LocationLocatorService::class)
      ->addArgument(new Reference('smartresponsor.locator_impl'));
  }
}
