<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Contract\Provider;
use Smartresponsor\Contract\LocatorInterface;
interface LocatorProviderInterface{
  public function getName(): string;
  public function getPriority(): int;
  public function isHealthy(): bool;
  public function getLocator(): LocatorInterface;
}
