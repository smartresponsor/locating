<?php
declare(strict_types=1);
namespace Smartresponsor\Contract\Locator\Provider;
use Smartresponsor\Contract\Locator\LocatorInterface;
interface LocatorProviderInterface{
  public function getName(): string;
  public function getPriority(): int;
  public function isHealthy(): bool;
  public function getLocator(): LocatorInterface;
}
