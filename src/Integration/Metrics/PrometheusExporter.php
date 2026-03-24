<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Integration\Metrics;

final class PrometheusExporter
{
    private array $counters = [];
    private array $breaker = [];

    public function inc(string $name, array $labels): void
    {
        $key = $name . '|' . json_encode($labels, JSON_THROW_ON_ERROR);
        $this->counters[$key] = ($this->counters[$key] ?? 0) + 1;
    }

    public function setBreaker(string $provider, string $state): void
    {
        $this->breaker[$provider] = $state;
    }

    public function render(): string
    {
        $lines = [
            '# HELP locator_provider_requests_total Requests by provider and outcome',
            '# TYPE locator_provider_requests_total counter',
        ];

        foreach ($this->counters as $k => $v) {
            [$name, $labels] = explode('|', $k, 2);
            $ls = json_decode($labels, true, 512, JSON_THROW_ON_ERROR);
            $pairs = [];
            foreach ($ls as $key => $value) {
                $pairs[] = sprintf('%s="%s"', $key, $value);
            }
            $lines[] = sprintf('%s{%s} %d', $name, implode(',', $pairs), $v);
        }

        $lines[] = '# HELP locator_breaker_state Breaker state by provider (1=open,0=closed,0.5=half_open)';
        $lines[] = '# TYPE locator_breaker_state gauge';
        foreach ($this->breaker as $provider => $state) {
            $value = $state === 'OPEN' ? 1.0 : ($state === 'HALF_OPEN' ? 0.5 : 0.0);
            $lines[] = sprintf('locator_breaker_state{provider="%s",state="%s"} %s', $provider, $state, $value);
        }

        return implode("\n", $lines) . "\n";
    }
}
