<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class ShadowTraffic implements ShadowTrafficInterface {
    /** @var array<string,int> */
    private array $count = [];
    public function pick(string $primary, array $candidate, float $ratio): string {
        if ($ratio <= 0.0) { return ''; }
        $cand = array_values(array_filter($candidate, fn($c)=>$c !== $primary));
        if (empty($cand)) { return ''; }
        $r = random_int(0, 1000) / 1000.0;
        return $r < min(1.0, max(0.0, $ratio)) ? (string)$cand[$r*1000 % count($cand)] : '';
    }
    public function record(string $primary, string $shadow, array $primaryResult, array $shadowResult): void {
        $k = $primary.'|'.$shadow;
        $this->count[$k] = ($this->count[$k] ?? 0) + 1;
        // could diff payloads and store mismatch stats here (omitted by design)
    }
}
