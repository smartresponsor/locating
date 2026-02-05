<?php
declare(strict_types=1);
namespace Smartresponsor\Integration\Locator\Provider;
use Smartresponsor\Contract\Locator\Provider\LocatorProviderInterface;
use Smartresponsor\Contract\Locator\LocatorInterface;
use Smartresponsor\Integration\Locator\Http\MapboxClient;
use Smartresponsor\Strategy\Locator\MapboxLocator;
final class MapboxProvider implements LocatorProviderInterface{
  public function __construct(private string $key, private int $timeout, private int $priority){}
  public function getName(): string{ return 'mapbox'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return $this->key !== ''; }
  public function getLocator(): LocatorInterface{ return new MapboxLocator(new MapboxClient($this->key, 'https://api.mapbox.com', $this->timeout)); }
}
