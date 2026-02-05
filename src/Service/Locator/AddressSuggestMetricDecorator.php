<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\Service\Locator;

use Smartresponsor\InfrastructureInterface\Locator\MetricRecorderInterface;
use Smartresponsor\ServiceInterface\Locator\AddressSuggestInterface;

/**
 * Decorator that records latency and count for AddressSuggestInterface.
 */
final class AddressSuggestMetricDecorator implements AddressSuggestInterface
{
    private AddressSuggestInterface $inner;

    private MetricRecorderInterface $metricRecorder;

    public function __construct(AddressSuggestInterface $inner, MetricRecorderInterface $metricRecorder)
    {
        $this->inner = $inner;
        $this->metricRecorder = $metricRecorder;
    }

    public function suggest(string $query, ?string $countryCode = null, int $limit = 5): array
    {
        $start = microtime(true);

        try {
            $items = $this->inner->suggest($query, $countryCode, $limit);
        } catch (\Throwable $exception) {
            $durationMs = (microtime(true) - $start) * 1000.0;
            $this->metricRecorder->recordLatency('address_suggest', $durationMs);
            $this->metricRecorder->incrementCounter('address_suggest', 'error');

            throw $exception;
        }

        $durationMs = (microtime(true) - $start) * 1000.0;

        $this->metricRecorder->recordLatency('address_suggest', $durationMs);
        $this->metricRecorder->incrementCounter('address_suggest', 'ok');

        return $items;
    }
}
