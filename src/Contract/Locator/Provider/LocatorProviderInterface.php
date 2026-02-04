<?php
declare(strict_types=1);
namespace SmartResponsor\Contract\Locator\Provider;
use SmartResponsor\Contract\Locator\LocatorInterface;
interface LocatorProviderInterface{
  public function getName(): string;
  public function getPriority(): int;
  public function isHealthy(): bool;
  public function getLocator(): LocatorInterface;
}
