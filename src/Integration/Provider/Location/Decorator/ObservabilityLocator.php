<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Decorator;

use App\Locating\Integration\Provider\Location\Metrics\Correlation\TraceContext;
use App\Locating\Model\Location\AddressData;
use App\Locating\Model\Location\GeoPoint;

final class ObservabilityLocator implements LocatorInterface
{
    public function __construct(private LocatorInterface $inner, private LoggerInterface $logger, private MetricsInterface $metrics)
    {
    }

    private function withObs(string $op, callable $fn, array $ctx = [])
    {
        TraceContext::child();
        $traceparent = TraceContext::traceparent();
        $start = microtime(true);
        $this->logger->info('locator.op.start', $ctx + ['op' => $op, 'traceparent' => $traceparent]);
        try {
            $result = $fn();
            $ms = (microtime(true) - $start) * 1000.0;
            $this->metrics->inc('locator_op_total', ['op' => $op, 'status' => 'ok']);
            $this->metrics->observeMs('locator_op_ms', $ms, ['op' => $op]);
            $this->logger->info('locator.op.done', $ctx + ['op' => $op, 'ms' => round($ms, 2), 'traceparent' => $traceparent]);

            return $result;
        } catch (\Throwable $e) {
            $ms = (microtime(true) - $start) * 1000.0;
            $this->metrics->inc('locator_op_total', ['op' => $op, 'status' => 'err']);
            $this->metrics->observeMs('locator_op_ms', $ms, ['op' => $op]);
            $this->logger->error('locator.op.fail', $ctx + ['op' => $op, 'ms' => round($ms, 2), 'err' => $e->getMessage(), 'traceparent' => $traceparent]);
            throw $e;
        }
    }

    public function normalize(string $raw): AddressData
    {
        return $this->withObs('normalize', fn () => $this->inner->normalize($raw), ['raw' => $raw]);
    }

    public function geocode(AddressData $a): GeoPoint
    {
        return $this->withObs('geocode', fn () => $this->inner->geocode($a), ['addr' => $a->toArray()]);
    }

    public function reverse(GeoPoint $p): AddressData
    {
        return $this->withObs('reverse', fn () => $this->inner->reverse($p), ['pt' => $p->toArray()]);
    }
}
