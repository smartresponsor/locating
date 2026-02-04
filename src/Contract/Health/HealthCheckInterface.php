<?php
declare(strict_types=1);
namespace SmartResponsor\Contract\Health;
interface HealthCheckInterface{
  /** @return array{status:string,details?:array<string,mixed>} */
  public function check(): array;
  public function name(): string;
}
