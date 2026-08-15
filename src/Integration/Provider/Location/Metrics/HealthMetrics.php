<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Metrics;

final class HealthMetrics
{
    private int $ok = 0;
    private int $fail = 0;
    /** @var list<float> */
    private array $lat = [];
    public function __construct(private int $maxSamples = 256)
    {
    }
    public function record(float $sec, bool $ok): void
    {
        if ($ok) {
            $this->ok++;
        } else {
            $this->fail++;
        }
        $this->lat[] = $sec;
        if (count($this->lat) > $this->maxSamples) {
            array_shift($this->lat);
        }
    }
    /** @return array{ok:int,fail:int,avg_ms:float,p95_ms:float,p99_ms:float,error_rate:float} */
    public function snapshot(): array
    {
        $total = $this->ok + $this->fail;
        $avg = 0.0;
        $p95 = 0.0;
        $p99 = 0.0;
        if ($this->lat !== []) {
            $avg = array_sum($this->lat) / count($this->lat);
            $sorted = $this->lat;
            sort($sorted);
            $p95 = $sorted[(int)floor(0.95 * (count($sorted) - 1))];
            $p99 = $sorted[(int)floor(0.99 * (count($sorted) - 1))];
        }
        $er = $total > 0 ? $this->fail / $total : 0.0;
        return ['ok' => $this->ok,'fail' => $this->fail,'avg_ms' => round($avg * 1000, 2),'p95_ms' => round($p95 * 1000, 2),'p99_ms' => round($p99 * 1000, 2),'error_rate' => $er];
    }
}
