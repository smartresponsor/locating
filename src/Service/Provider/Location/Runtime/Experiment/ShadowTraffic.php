<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Experiment;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Experiment\ShadowTrafficInterface;

final class ShadowTraffic implements ShadowTrafficInterface
{
    /** @var array<string,int> */
    private array $count = [];
    /** @param list<string> $candidate */
    public function pick(string $primary, array $candidate, float $ratio): string
    {
        if ($ratio <= 0.0) {
            return '';
        }
        $cand = array_values(array_filter($candidate, static fn (string $provider): bool => $provider !== $primary));
        if ([] === $cand) {
            return '';
        }
        $r = random_int(0, 1000) / 1000.0;
        if ($r >= min(1.0, max(0.0, $ratio))) {
            return '';
        }

        return $cand[random_int(0, count($cand) - 1)];
    }

    /**
     * @param array<string,mixed> $primaryResult
     * @param array<string,mixed> $shadowResult
     */
    public function record(string $primary, string $shadow, array $primaryResult, array $shadowResult): void
    {
        $k = $primary.'|'.$shadow;
        $this->count[$k] = ($this->count[$k] ?? 0) + 1;
        // could diff payloads and store mismatch stats here (omitted by design)
    }
}
