<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Provider;
use SmartResponsor\Contract\Locator\Provider\LocatorProviderInterface;
use SmartResponsor\Contract\Locator\LocatorInterface;
use SmartResponsor\Integration\Locator\Http\MapboxClient;
use SmartResponsor\Strategy\Locator\MapboxLocator;
final class MapboxProvider implements LocatorProviderInterface{
  public function __construct(private string $key, private int $timeout, private int $priority){}
  public function getName(): string{ return 'mapbox'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return $this->key !== ''; }
  public function getLocator(): LocatorInterface{ return new MapboxLocator(new MapboxClient($this->key, 'https://api.mapbox.com', $this->timeout)); }
}
