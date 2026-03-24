<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Provider;
use App\Contract\Provider\LocatorProviderInterface;
use App\Contract\LocatorInterface;
use App\Integration\Http\GoogleGeocodingClient;
use App\Strategy\GoogleLocator;
final class GoogleProvider implements LocatorProviderInterface{
  public function __construct(private string $key, private int $timeout, private int $priority){}
  public function getName(): string{ return 'google'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return $this->key !== ''; }
  public function getLocator(): LocatorInterface{ return new GoogleLocator(new GoogleGeocodingClient($this->key, 'https://maps.googleapis.com', $this->timeout)); }
}
