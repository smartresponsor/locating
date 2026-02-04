<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Entity\Locator;

use App\ServiceInterface\Locator\HealthRecorderInterface;
final class HealthRecorder implements HealthRecorderInterface {
    /** @var array<string, array{ok:int, fail:int, total_ms:float}> */
    private array $st = [];
    public function ok(string $key, float $latencyMs): void { $this->add($key, true, $latencyMs); }
    public function fail(string $key, float $latencyMs): void { $this->add($key, false, $latencyMs); }
    private function add(string $key, bool $ok, float $latencyMs): void {
        $r = $this->st[$key] ?? ['ok'=>0,'fail'=>0,'total_ms'=>0.0];
        if ($ok) { $r['ok'] += 1; } else { $r['fail'] += 1; }
        $r['total_ms'] += max(0.0, $latencyMs);
        $this->st[$key] = $r;
    }
    public function snapshot(string $key): array {
        $r = $this->st[$key] ?? ['ok'=>0,'fail'=>0,'total_ms'=>0.0];
        $n = max(1, $r['ok'] + $r['fail']);
        return ['ok'=>$r['ok'],'fail'=>$r['fail'],'avg_ms'=>$r['total_ms']/$n,'error_rate'=>($r['fail']/$n)];
    }
}
