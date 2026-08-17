<?php

# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Metrics;

final class PrometheusExporter
{
    /** @var array<string, int> */
    private array $counters = [];

    /** @var array<string, string> */
    private array $breaker = [];

    /** @param array<string,string|int|float|bool> $labels */
    public function inc(string $nameEntity, array $labels): void
    {
        $key = $nameEntity . '|' . json_encode($labels, JSON_THROW_ON_ERROR);
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
            [$nameEntity, $labels] = explode('|', $k, 2);
            $decoded = json_decode($labels, true, 512, JSON_THROW_ON_ERROR);
            $pairs = [];
            if (is_array($decoded)) {
                foreach ($decoded as $key => $value) {
                    if (is_string($key) && is_scalar($value)) {
                        $pairs[] = sprintf('%s="%s"', $key, (string) $value);
                    }
                }
            }
            $lines[] = sprintf('%s{%s} %d', $nameEntity, implode(',', $pairs), $v);
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
