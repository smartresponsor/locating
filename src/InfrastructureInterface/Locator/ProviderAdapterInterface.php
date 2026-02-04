<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\InfrastructureInterface\Locator;

/**
 * Low-level adapter for concrete location provider.
 *
 * Implementations are responsible for translating a normalized request
 * into a remote API call and mapping response back into a generic array
 * structure that higher layers can consume.
 *
 * Contract:
 * - MUST return an array with at least:
 *   - 'status' => 'ok'|'timeout'|'error'|'bad_request'|'quota_exceeded'
 *   - any provider-specific payload fields (coordinates, text, etc.)
 */
interface ProviderAdapterInterface
{
    /**
     * Execute provider request and return normalized payload.
     *
     * @param array<string,mixed> $request
     * @return array<string,mixed>
     */
    public function call(array $request): array;
}
