<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Metrics;

use App\Locating\Contract\Location\LocationMetricsContract as MetricsInterface;

final class InMemoryMetrics implements MetricsInterface
{
    /** @var array<string,float> */
    private array $counters = [];
    /** @var array<string,list<float>> */
    private array $timings = [];

    public function inc(string $nameEntity, array $labels = []): void
    {
        $key = $this->key($nameEntity, $labels);
        $this->counters[$key] = ($this->counters[$key] ?? 0) + 1;
    }

    public function observeMs(string $nameEntity, float $ms, array $labels = []): void
    {
        $key = $this->key($nameEntity, $labels);
        $this->timings[$key][] = $ms;
    }

    public function snapshot(): array
    {
        return ['counters' => $this->counters, 'timings' => $this->timings];
    }

    /** @param array<string,string> $labels */
    private function key(string $nameEntity, array $labels): string
    {
        ksort($labels);
        $pairs = [];
        foreach ($labels as $k => $v) {
            $pairs[] = $k.'='.$v;
        }

        return $nameEntity.'{'.implode(',', $pairs).'}';
    }
}
