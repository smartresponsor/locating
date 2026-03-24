<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Contract\Provider;
use App\Contract\LocatorInterface;
interface LocatorProviderInterface{
  public function getName(): string;
  public function getPriority(): int;
  public function isHealthy(): bool;
  public function getLocator(): LocatorInterface;
}
