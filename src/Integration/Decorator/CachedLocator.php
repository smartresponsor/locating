<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Decorator;
use Smartresponsor\Contract\LocatorInterface;
use Smartresponsor\Integration\Cache\SimpleArrayCache;
use Smartresponsor\Model\AddressData;
use Smartresponsor\Model\GeoPoint;
final class CachedLocator implements LocatorInterface{
  public function __construct(private LocatorInterface $inner, private SimpleArrayCache $cache, private int $ttl = 600){}
  private function key(string $p, string $s): string { return 'locator:' . $p . ':' . sha1($s); }
  public function normalize(string $raw): AddressData {
    $k = $this->key('n', $raw);
    $h = $this->cache->get($k);
    if ($h instanceof AddressData) return $h;
    $r = $this->inner->normalize($raw);
    $this->cache->set($k, $r, $this->ttl);
    return $r;
  }
  public function geocode(AddressData $a): GeoPoint {
    $payload = implode('|', [$a->street,$a->city,$a->region,$a->postalCode,$a->countryCode]);
    $k = $this->key('g', $payload);
    $h = $this->cache->get($k);
    if ($h instanceof GeoPoint) return $h;
    $r = $this->inner->geocode($a);
    $this->cache->set($k, $r, $this->ttl);
    return $r;
  }
  public function reverse(GeoPoint $p): AddressData {
    $k = $this->key('r', "{$p->latitude},{$p->longitude}");
    $h = $this->cache->get($k);
    if ($h instanceof AddressData) return $h;
    $r = $this->inner->reverse($p);
    $this->cache->set($k, $r, $this->ttl);
    return $r;
  }
}
