<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Cache;

final class FileCache
{
    private string $dir;
    public function __construct(private int $ttl = 600, private int $staleTtl = 86400)
    {
        $this->dir = sys_get_temp_dir().'/locator_cache';
        if (!is_dir($this->dir)) {
            @mkdir($this->dir, 0755, true);
        }
    }
    private function path(string $key): string
    {
        return $this->dir.'/'.sha1($key).'.json';
    }
    /** @return array{hit:bool,stale?:bool,value?:array<string,mixed>} */
    public function get(string $key): array
    {
        $p = $this->path($key);
        if (!file_exists($p)) {
            return ['hit' => false];
        }
        $decoded = json_decode((string) file_get_contents($p), true);
        if (!is_array($decoded) || !is_numeric($decoded['ts'] ?? null) || !is_array($decoded['value'] ?? null)) {
            return ['hit' => false];
        }
        $value = [];
        foreach ($decoded['value'] as $valueKey => $entry) {
            if (is_string($valueKey)) {
                $value[$valueKey] = $entry;
            }
        }
        $age = time() - (int) $decoded['ts'];
        if ($age <= $this->ttl) {
            return ['hit' => true, 'stale' => false, 'value' => $value];
        }
        if ($age <= $this->staleTtl) {
            return ['hit' => true, 'stale' => true, 'value' => $value];
        }

        return ['hit' => false];
    }

    /** @param array<string,mixed> $value */
    public function set(string $key, array $value): void
    {
        file_put_contents($this->path($key), json_encode(['ts' => time(), 'value' => $value], JSON_THROW_ON_ERROR));
    }
}
