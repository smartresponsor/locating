<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Provider;
use App\Contract\Provider\LocatorProviderInterface;
use App\Contract\LocatorInterface;
use App\Integration\Http\MapboxClient;
use App\Strategy\MapboxLocator;
final class MapboxProvider implements LocatorProviderInterface{
  public function __construct(private string $key, private int $timeout, private int $priority){}
  public function getName(): string{ return 'mapbox'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return $this->key !== ''; }
  public function getLocator(): LocatorInterface{ return new MapboxLocator(new MapboxClient($this->key, 'https://api.mapbox.com', $this->timeout)); }
}
