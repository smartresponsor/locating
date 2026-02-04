<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Provider;
use SmartResponsor\Contract\Locator\Provider\LocatorProviderInterface;
use SmartResponsor\Contract\Locator\LocatorInterface;
use SmartResponsor\Integration\Locator\Http\NominatimClient;
use SmartResponsor\Strategy\Locator\OpenStreetMapLocator;
final class OpenStreetMapProvider implements LocatorProviderInterface{
  public function __construct(private string $base, private ?string $email, private int $timeout, private int $priority){}
  public function getName(): string{ return 'osm'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return (new NominatimClient($this->base,$this->email,2))->search('London')!==[]; }
  public function getLocator(): LocatorInterface{ return new OpenStreetMapLocator(new NominatimClient($this->base, $this->email, $this->timeout)); }
}
