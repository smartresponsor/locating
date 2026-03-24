<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration;
use App\Contract\LocatorInterface;
use App\Integration\Provider\Dsn\LocatorDsn;
use App\Integration\Provider\{OpenStreetMapProvider,FallbackProvider};
use App\Integration\Registry\LocatorPluginRegistry;
use App\Integration\Cache\LruCache;
use App\Integration\Cache\AdaptiveTtlPolicy;
use App\Integration\Decorator\{RetryLocator,CircuitBreakerLocator,AdaptiveMultiCachedLocator,HealthProbeLocator};
use App\Integration\Metrics\{HealthMetrics,PrometheusExporter};
final class LocatorSelector{
  private LocatorPluginRegistry $registry;
  private LruCache $cache;
  private HealthMetrics $metrics;
  private PrometheusExporter $exp;
  private AdaptiveTtlPolicy $ttl;
  public function __construct(private LocatorConfig $cfg){
    $this->registry = new LocatorPluginRegistry();
    foreach ($this->cfg->providers as $dsn){ $this->registerFromDsn(new LocatorDsn($dsn)); }
    $this->cache = new LruCache($this->cfg->cacheMax);
    $this->metrics = new HealthMetrics($this->cfg->metricsWindow);
    $this->ttl = new AdaptiveTtlPolicy($this->cfg->minTtl,$this->cfg->baseTtl,$this->cfg->maxTtl);
    $this->exp = new PrometheusExporter($this->metrics, $this->cache);
  }
  private function registerFromDsn(LocatorDsn $d): void{
    $priority = (int)($d->get('priority','0'));
    switch ($d->scheme){
      case 'osm':
        $this->registry->register(new OpenStreetMapProvider(
          $d->get('base','https://nominatim.openstreetmap.org') ?? 'https://nominatim.openstreetmap.org',
          $d->get('email', null),
          (int)($d->get('timeout','10')),
          $priority
        ));
        break;
      case 'fallback':
        $this->registry->register(new FallbackProvider($priority));
        break;
      default:
        throw new \InvalidArgumentException('Unknown provider scheme: '.$d->scheme);
    }
  }
  public function getActive(): LocatorInterface{
    $p = $this->registry->healthiest();
    if (!$p){ $p = new FallbackProvider(0); }
    $loc = $p->getLocator();
    $loc = new RetryLocator($loc, $this->cfg->retries, $this->cfg->retryBaseMs);
    $loc = new CircuitBreakerLocator($loc, $this->cfg->cbThreshold, $this->cfg->cbCooldownSec);
    $loc = new AdaptiveMultiCachedLocator($loc, $this->cache, $this->ttl, $this->metrics, $this->cfg->negCacheTtl);
    $loc = new HealthProbeLocator($loc, $this->metrics);
    return $loc;
  }
  public function cacheStats(): array{ return $this->cache->stats(); }
  public function metrics(): array{ return $this->metrics->snapshot(); }
  public function prometheus(): string{ return $this->exp->render(); }
  public function describeProviders(): array{
    $out=[]; foreach($this->registry->all() as $p){ $out[]=['name'=>$p->getName(),'priority'=>$p->getPriority(),'healthy'=>$p->isHealthy()]; }
    return $out;
  }
}
