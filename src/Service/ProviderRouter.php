<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;

use App\Entity\ProviderSandbox;
use App\EntityInterface\AddressInputInterface;
use App\InfrastructureInterface\MetricRecorderInterface;
use App\ServiceInterface\FailoverPlannerInterface;
use App\ServiceInterface\HealthRecorderInterface;
use App\ServiceInterface\ProviderRouterInterface;

/**
 * ProviderRouter orchestrates provider calls with failover and simple SLA awareness.
 *
 * It does not know about HTTP or external APIs directly – only about ProviderAdapterInterface
 * registered inside ProviderSandbox and high-level metrics provided by HealthRecorderInterface.
 */
final class ProviderRouter implements ProviderRouterInterface
{
    public function __construct(
        private ProviderSandbox $sandbox,
        private FailoverPlannerInterface $failoverPlanner,
        private HealthRecorderInterface $healthRecorder,
        private MetricRecorderInterface $metricRecorder,
    ) {
    }

    public function route(AddressInputInterface $input, string $region, string $tenantId, array $provider): array
    {
        $provider = array_values(array_unique(array_filter($provider, 'strlen')));
        if ($provider === []) {
            return [
                'status' => 'error',
                'error' => 'no_provider_configured',
                '_provider' => null,
                'latencyMs' => 0.0,
            ];
        }

        // Build signal map for FailoverPlanner.
        $signal = [];
        foreach ($provider as $id) {
            $snap = $this->healthRecorder->snapshot('provider:' . $id);
            $signal[$id] = [
                'p95_ms' => (float)($snap['avg_ms'] ?? 300.0),
                'error_rate' => (float)($snap['error_rate'] ?? 0.02),
            ];
        }

        $chain = $this->failoverPlanner->plan($region, $provider, $signal);
        if ($chain === []) {
            $chain = $provider;
        }

        $baseRequest = [
            'q' => $input->rawLine(),
            'tenant' => $tenantId,
            'region' => $region,
            'input' => $input->toArray(),
        ];

        $lastLatency = 0.0;
        $lastError = null;

        foreach ($chain as $id) {
            $start = microtime(true);
            try {
                $result = $this->sandbox->route($id, $baseRequest);
                $ms = (microtime(true) - $start) * 1000.0;
                $lastLatency = $ms;

                $status = (string)($result['status'] ?? 'ok');
                $this->metricRecorder->recordLatency('provider_' . $id, $ms);

                if ($status === 'ok') {
                    $this->healthRecorder->ok('provider:' . $id, $ms);
                    $this->metricRecorder->incrementCounter('provider_' . $id, 'ok');
                    $result['latencyMs'] = $ms;

                    return $result;
                }

                // Non-ok status – treat as failure and move on to next provider.
                $this->healthRecorder->fail('provider:' . $id, $ms);
                $this->metricRecorder->incrementCounter('provider_' . $id, $status);
                $lastError = $status;
            } catch (\Throwable $e) {
                $ms = (microtime(true) - $start) * 1000.0;
                $lastLatency = $ms;
                $this->healthRecorder->fail('provider:' . $id, $ms);
                $this->metricRecorder->recordLatency('provider_' . $id, $ms);
                $this->metricRecorder->incrementCounter('provider_' . $id, 'exception');
                $lastError = $e->getMessage();
                // Continue to next provider in chain.
            }
        }

        return [
            'status' => 'error',
            'error' => $lastError ?? 'all_providers_failed',
            '_provider' => null,
            'latencyMs' => $lastLatency,
            'providerList' => $chain,
        ];
    }
}
