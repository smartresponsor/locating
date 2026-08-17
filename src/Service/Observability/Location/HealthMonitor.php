<?php

declare(strict_types=1);

namespace App\Locating\Service\Observability\Location;

final class HealthMonitor
{
    private string $path;

    /** @var array<string, array{ok:int, fail:int, ewma_ms:float, last:int}> */
    private array $state = [];
    public function __construct(?string $file = null)
    {
        $this->path = $file ?: sys_get_temp_dir().'/locator_health.json';
        if (is_file($this->path)) {
            $decoded = json_decode((string) file_get_contents($this->path), true);
            if (is_array($decoded)) {
                foreach ($decoded as $provider => $row) {
                    if (!is_string($provider) || !is_array($row)) {
                        continue;
                    }
                    $this->state[$provider] = [
                        'ok' => is_numeric($row['ok'] ?? null) ? (int) $row['ok'] : 0,
                        'fail' => is_numeric($row['fail'] ?? null) ? (int) $row['fail'] : 0,
                        'ewma_ms' => is_numeric($row['ewma_ms'] ?? null) ? (float) $row['ewma_ms'] : 500.0,
                        'last' => is_numeric($row['last'] ?? null) ? (int) $row['last'] : 0,
                    ];
                }
            }
        }
    }
    public function update(string $provider, bool $ok, float $ms): void
    {
        if (!isset($this->state[$provider])) {
            $this->state[$provider] = ['ok' => 0,'fail' => 0,'ewma_ms' => 500.0,'last' => 0];
        }
        $alpha = 0.3;
        $cur = $this->state[$provider];
        $cur['ewma_ms'] = $alpha * $ms + (1 - $alpha) * $cur['ewma_ms'];
        if ($ok) {
            $cur['ok']++;
        } else {
            $cur['fail']++;
        }
        $cur['last'] = time();
        $this->state[$provider] = $cur;
        $this->flush();
    }
    private function flush(): void
    {
        @file_put_contents($this->path, json_encode($this->state));
    }
    /**
     * @return array<string, array{ok:int, fail:int, successRate:float, ewmaMs:float, score:int, last:int}>
     */
    public function snapshot(): array
    {
        $out = [];
        foreach ($this->state as $p => $s) {
            $total = max(1, $s['ok'] + $s['fail']);
            $sr = $s['ok'] / $total;
            $score = (int)round($sr * 100 - min(1000.0, $s['ewma_ms']));
            $out[$p] = ['ok' => $s['ok'],'fail' => $s['fail'],'successRate' => $sr,'ewmaMs' => $s['ewma_ms'],'score' => $score,'last' => $s['last']];
        }
        return $out;
    }
}
