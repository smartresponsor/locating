<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace Smartresponsor\Service;

use Smartresponsor\Entity\AddressInput;
use Smartresponsor\Entity\AddressResult;
use Smartresponsor\InfrastructureInterface\MetricRecorderInterface;
use Smartresponsor\ServiceInterface\AddressPipelineInterface;

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
