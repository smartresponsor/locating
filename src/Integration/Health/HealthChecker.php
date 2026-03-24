<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Health;
use Smartresponsor\Integration\LocatorSelector;
final class HealthChecker{
  public function ping(LocatorSelector $sel): array{ return ['ok'=>true,'active'=>get_class($sel->getActiveStrategy())]; }
}