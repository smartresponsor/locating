<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Provider;
use App\Contract\Provider\LocatorProviderInterface;
use App\Contract\LocatorInterface;
use App\Integration\Fallback\FallbackLocator;
final class FallbackProvider implements LocatorProviderInterface{
  public function __construct(private int $priority=0){}
  public function getName(): string{ return 'fallback'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return true; }
  public function getLocator(): LocatorInterface{ return new FallbackLocator(); }
}
