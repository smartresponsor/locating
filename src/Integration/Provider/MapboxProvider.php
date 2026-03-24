<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Provider;
use Smartresponsor\Contract\Provider\LocatorProviderInterface;
use Smartresponsor\Contract\LocatorInterface;
use Smartresponsor\Integration\Http\MapboxClient;
use Smartresponsor\Strategy\MapboxLocator;
final class MapboxProvider implements LocatorProviderInterface{
  public function __construct(private string $key, private int $timeout, private int $priority){}
  public function getName(): string{ return 'mapbox'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return $this->key !== ''; }
  public function getLocator(): LocatorInterface{ return new MapboxLocator(new MapboxClient($this->key, 'https://api.mapbox.com', $this->timeout)); }
}
