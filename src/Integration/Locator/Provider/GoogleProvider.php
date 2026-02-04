<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Provider;
use SmartResponsor\Contract\Locator\Provider\LocatorProviderInterface;
use SmartResponsor\Contract\Locator\LocatorInterface;
use SmartResponsor\Integration\Locator\Http\GoogleGeocodingClient;
use SmartResponsor\Strategy\Locator\GoogleLocator;
final class GoogleProvider implements LocatorProviderInterface{
  public function __construct(private string $key, private int $timeout, private int $priority){}
  public function getName(): string{ return 'google'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return $this->key !== ''; }
  public function getLocator(): LocatorInterface{ return new GoogleLocator(new GoogleGeocodingClient($this->key, 'https://maps.googleapis.com', $this->timeout)); }
}
