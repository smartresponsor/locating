<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Provider\Dsn;

final class LocatorDsn
{
    public string $scheme;
    /** @var array<string,string> */
    public array $query;
    public function __construct(public string $dsn)
    {
        $p = parse_url($dsn);
        if (!$p || !isset($p['scheme'])) {
            throw new \InvalidArgumentException('Invalid DSN: '.$dsn);
        }
        $this->scheme = strtolower($p['scheme']);
        $q = [];
        if (isset($p['query'])) {
            parse_str($p['query'], $q);
        }
        $query = [];
        foreach ($q as $key => $value) {
            if (is_string($key) && is_scalar($value)) {
                $query[$key] = (string) $value;
            }
        }
        $this->query = $query;
    }
    public function get(string $key, ?string $default = null): ?string
    {
        return $this->query[$key] ?? $default;
    }
}
