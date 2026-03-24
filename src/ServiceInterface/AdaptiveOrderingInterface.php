<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface AdaptiveOrderingInterface {
    /**
     * Order providers by composite score. Inputs:
     * - $provider: list of provider ids
     * - $signal: map id => ['latency_ms'=>float,'error_rate'=>float,'health'=>float]
     * - $cost: map id => unit cost
     * - $hint: array of hint tags (country, etc.)
     * Return: ordered ids best-first.
     */
    public function order(string $region, array $provider, array $signal, array $cost, array $hint): array;
}
