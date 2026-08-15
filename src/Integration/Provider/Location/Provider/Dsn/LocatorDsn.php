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
        $this->query = array_map(fn ($v) => (string)$v, $q);
    }
    public function get(string $key, ?string $default = null): ?string
    {
        return $this->query[$key] ?? $default;
    }
}
