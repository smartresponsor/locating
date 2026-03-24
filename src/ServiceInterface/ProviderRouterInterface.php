<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\ServiceInterface;

use Smartresponsor\EntityInterface\AddressInputInterface;

/**
 * Provider-level router with failover and basic SLA awareness.
 */
interface ProviderRouterInterface
{
    /**
     * Route a single address query through providers with failover.
     *
     * @param AddressInputInterface $input   Normalized address input.
     * @param string                $region  Region key (for SLA and policy).
     * @param string                $tenantId Tenant identifier for metrics/quota tags.
     * @param string[]              $provider List of provider identifiers.
     *
     * @return array<string,mixed> Normalized provider payload:
     *                             - status: 'ok'|'timeout'|'error'|'bad_request'|'quota_exceeded'
     *                             - _provider: provider id that produced the result (or null)
     *                             - latencyMs: float latency of the final attempt
     */
    public function route(AddressInputInterface $input, string $region, string $tenantId, array $provider): array;
}
