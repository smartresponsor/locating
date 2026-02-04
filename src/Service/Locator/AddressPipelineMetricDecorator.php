<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Service\Locator;

use App\Entity\Locator\AddressInput;
use App\Entity\Locator\AddressResult;
use App\InfrastructureInterface\Locator\MetricRecorderInterface;
use App\ServiceInterface\Locator\AddressPipelineInterface;

/**
 * Decorator that records latency and success/error counters for AddressPipelineInterface.
 */
final class AddressPipelineMetricDecorator implements AddressPipelineInterface
{
    private AddressPipelineInterface $inner;

    private MetricRecorderInterface $metricRecorder;

    public function __construct(AddressPipelineInterface $inner, MetricRecorderInterface $metricRecorder)
    {
        $this->inner = $inner;
        $this->metricRecorder = $metricRecorder;
    }

    public function process(AddressInput $input): AddressResult
    {
        $start = microtime(true);

        try {
            $result = $this->inner->process($input);
        } catch (\Throwable $exception) {
            $durationMs = (microtime(true) - $start) * 1000.0;

            $this->metricRecorder->recordLatency('address_pipeline', $durationMs);
            $this->metricRecorder->incrementCounter('address_pipeline', 'error');

            throw $exception;
        }

        $durationMs = (microtime(true) - $start) * 1000.0;

        $this->metricRecorder->recordLatency('address_pipeline', $durationMs);
        $this->metricRecorder->incrementCounter('address_pipeline', 'ok');

        return $result;
    }
}
