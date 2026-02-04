<?php
declare(strict_types=1);
namespace SmartResponsor\Contract\Metrics;
interface MetricsInterface{
  /** @param array<string,string> $labels */
  public function inc(string $name, array $labels=[]): void;
  /** @param array<string,string> $labels */
  public function observeMs(string $name, float $ms, array $labels=[]): void;
  /** @return array<string,mixed> */
  public function snapshot(): array;
}
