<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Health;
use SmartResponsor\Integration\Locator\LocatorSelector;
final class HealthChecker{
  public function ping(LocatorSelector $sel): array{ return ['ok'=>true,'active'=>get_class($sel->getActiveStrategy())]; }
}