<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Provider;
use Smartresponsor\Contract\Provider\LocatorProviderInterface;
use Smartresponsor\Contract\LocatorInterface;
use Smartresponsor\Integration\Http\NominatimClient;
use Smartresponsor\Strategy\OpenStreetMapLocator;
final class OpenStreetMapProvider implements LocatorProviderInterface{
  public function __construct(private string $base, private ?string $email, private int $timeout, private int $priority){}
  public function getName(): string{ return 'osm'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return (new NominatimClient($this->base,$this->email,2))->search('London')!==[]; }
  public function getLocator(): LocatorInterface{ return new OpenStreetMapLocator(new NominatimClient($this->base, $this->email, $this->timeout)); }
}
