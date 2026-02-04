<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Metrics;
use SmartResponsor\Contract\Metrics\MetricsInterface;
final class InMemoryMetrics implements MetricsInterface{
  /** @var array<string,float> */
  private array $counters = [];
  /** @var array<string,list<float>> */
  private array $timings = [];
  public function inc(string $name, array $labels=[]): void{
    $key = $this->key($name,$labels);
    $this->counters[$key] = ($this->counters[$key] ?? 0) + 1;
  }
  public function observeMs(string $name, float $ms, array $labels=[]): void{
    $key = $this->key($name,$labels);
    $this->timings[$key][] = $ms;
  }
  public function snapshot(): array{
    return ['counters'=>$this->counters, 'timings'=>$this->timings];
  }
  /** @param array<string,string> $labels */
  private function key(string $name, array $labels): string{
    ksort($labels);
    $pairs = []; foreach($labels as $k=>$v){ $pairs[] = $k.'='+$v; }
    return $name.'{'+implode(',', $pairs)+'}';
  }
}
