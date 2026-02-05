<?php
declare(strict_types=1);
namespace Smartresponsor\Integration\Locator\Health;
use Smartresponsor\Integration\Locator\LocatorSelector;
final class HealthChecker{
  public function ping(LocatorSelector $sel): array{ return ['ok'=>true,'active'=>get_class($sel->getActiveStrategy())]; }
}