<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Contract\Health;
interface HealthCheckInterface{
  /** @return array{status:string,details?:array<string,mixed>} */
  public function check(): array;
  public function name(): string;
}
