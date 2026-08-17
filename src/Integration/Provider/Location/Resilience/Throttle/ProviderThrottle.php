<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Resilience\Throttle;

final class ProviderThrottle
{
    private string $dir;

    /** @var array<string, array{limit:int,window:int}> */
    private array $cfg = [];
    public function __construct(string $spec)
    {
        $this->dir = sys_get_temp_dir().'/locator_throttle';
        if (!is_dir($this->dir)) {
            @mkdir($this->dir, 0755, true);
        }
        foreach (array_filter(array_map('trim', explode(',', $spec))) as $p) {
            [$prov,$lim] = array_pad(explode(':', $p, 2), 2, '');
            if (!$prov || !$lim) {
                continue;
            } [$c,$s] = array_map('intval', explode('/', $lim, 2));
            $this->cfg[$prov] = ['limit' => $c,'window' => $s];
        }
    }
    private function path(string $prov): string
    {
        return $this->dir.'/'.sha1($prov).'.json';
    }
    public function allow(string $prov): bool
    {
        $c = $this->cfg[$prov] ?? ['limit' => 100,'window' => 1];
        $p = $this->path($prov);
        $now = time();
        $state = ['ts' => $now, 'cnt' => 0];
        if (file_exists($p)) {
            $decoded = json_decode((string) file_get_contents($p), true);
            if (is_array($decoded)) {
                $state = [
                    'ts' => is_numeric($decoded['ts'] ?? null) ? (int) $decoded['ts'] : $now,
                    'cnt' => is_numeric($decoded['cnt'] ?? null) ? (int) $decoded['cnt'] : 0,
                ];
            }
        }
        if ($now - $state['ts'] >= $c['window']) {
            $state = ['ts' => $now, 'cnt' => 0];
        }
        $ok = $state['cnt'] < $c['limit'];
        if ($ok) {
            ++$state['cnt'];
            file_put_contents($p, json_encode($state, JSON_THROW_ON_ERROR));
        }
        return $ok;
    }
}
