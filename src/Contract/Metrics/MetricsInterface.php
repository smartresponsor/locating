<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Contract\Metrics;
interface MetricsInterface{
  /** @param array<string,string> $labels */
  public function inc(string $name, array $labels=[]): void;
  /** @param array<string,string> $labels */
  public function observeMs(string $name, float $ms, array $labels=[]): void;
  /** @return array<string,mixed> */
  public function snapshot(): array;
}
