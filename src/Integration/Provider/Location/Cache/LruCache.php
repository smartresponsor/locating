<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Cache;

final class LruCache implements CacheInterface
{
    /** @var array<string,array{v:mixed,exp:int}> */
    private array $data = [];
    /** @var string[] */
    private array $order = [];
    private int $hits = 0;
    private int $misses = 0;
    public function __construct(private int $max = 256)
    {
    }
    private function touch(string $key): void
    {
        $i = array_search($key, $this->order, true);
        if ($i !== false) {
            array_splice($this->order, $i, 1);
        }
        $this->order[] = $key;
        if (count($this->order) > $this->max) {
            $old = array_shift($this->order);
            if ($old !== null) {
                unset($this->data[$old]);
            }
        }
    }
    public function get(string $key): mixed
    {
        if (!isset($this->data[$key])) {
            $this->misses++;
            return null;
        }
        $e = $this->data[$key];
        if ($e['exp'] < time()) {
            unset($this->data[$key]);
            $this->misses++;
            return null;
        }
        $this->hits++;
        $this->touch($key);
        return $e['v'];
    }
    public function set(string $key, mixed $value, int $ttlSec): void
    {
        $this->data[$key] = ['v' => $value,'exp' => time() + $ttlSec];
        $this->touch($key);
    }
    public function delete(string $key): void
    {
        unset($this->data[$key]);
        $i = array_search($key, $this->order, true);
        if ($i !== false) {
            array_splice($this->order, $i, 1);
        }
    }
    public function clear(): void
    {
        $this->data = [];
        $this->order = [];
        $this->hits = $this->misses = 0;
    }
    public function stats(): array
    {
        return ['items' => count($this->data),'capacity' => $this->max,'hits' => $this->hits,'misses' => $this->misses];
    }
}
