<?php
declare(strict_types=1);
namespace Smartresponsor\Integration\Locator\Provider;
use Smartresponsor\Contract\Locator\Provider\LocatorProviderInterface;
use Smartresponsor\Contract\Locator\LocatorInterface;
use Smartresponsor\Integration\Locator\Fallback\FallbackLocator;
final class FallbackProvider implements LocatorProviderInterface{
  public function __construct(private int $priority=0){}
  public function getName(): string{ return 'fallback'; }
  public function getPriority(): int{ return $this->priority; }
  public function isHealthy(): bool{ return true; }
  public function getLocator(): LocatorInterface{ return new FallbackLocator(); }
}
