<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Resilience;

final class CircuitBreaker
{
    private string $path;
    public function __construct(private string $nameEntity, private int $failThreshold = 3, private int $openSeconds = 30)
    {
        $dir = sys_get_temp_dir().'/locator_cb';
        if (!is_dir($dir)) {
            @mkdir($dir, 0755, true);
        } $this->path = $dir.'/'.sha1($nameEntity).'.json';
    }
    private function read(): array
    {
        if (!file_exists($this->path)) {
            return ['state' => 'CLOSED','fails' => 0,'openedAt' => 0];
        }
        return json_decode((string)file_get_contents($this->path), true) ?: ['state' => 'CLOSED','fails' => 0,'openedAt' => 0];
    }
    private function write(array $s): void
    {
        file_put_contents($this->path, json_encode($s));
    }
    public function allow(): bool
    {
        $s = $this->read();
        if ($s['state'] === 'OPEN') {
            if (time() - (int)$s['openedAt'] >= $this->openSeconds) {
                $s['state'] = 'HALF_OPEN';
                $this->write($s);
                return true;
            }
            return false;
        }
        return true;
    }
    public function recordSuccess(): void
    {
        $this->write(['state' => 'CLOSED','fails' => 0,'openedAt' => 0]);
    }
    public function recordFailure(): void
    {
        $s = $this->read();
        $s['fails'] = ((int)$s['fails']) + 1;
        if ($s['fails'] >= $this->failThreshold) {
            $s['state'] = 'OPEN';
            $s['openedAt'] = time();
        }
        $this->write($s);
    }
    public function state(): string
    {
        return $this->read()['state'];
    }
}
