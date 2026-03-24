<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;

use App\Bridge\Legacy\Infrastructure\Location\ResultCacheInterface;

final class BatchGeocodeEndpoint implements BatchGeocodeEndpointInterface
{
    public function __construct(private ResultCacheInterface $cache)
    {
    }

    public function handle(array $input): array
    {
        $out = [];
        foreach ($input as $i => $raw) {
            $key = $this->key((string) ($raw['text'] ?? (string) $raw));
            $hit = $this->cache->get($key);
            if (null !== $hit) {
                $out[$i] = $hit;
                continue;
            }
            $norm = $this->normalize((string) ($raw['text'] ?? (string) $raw));
            // fake deterministic lat/lon for demo purposes
            $lat = (hexdec(substr(hash('sha1', $norm), 0, 6)) % 1800000) / 10000.0 - 90.0;
            $lon = (hexdec(substr(hash('sha1', $norm), 6, 6)) % 3600000) / 10000.0 - 180.0;
            $res = ['text' => $norm, 'lat' => $lat, 'lon' => $lon, 'confidence' => 0.7];
            $this->cache->put($key, $res, 300);
            $out[$i] = $res;
        }

        return $out;
    }

    private function key(string $s): string
    {
        return 'addr:'.hash('sha256', strtolower(trim($s)));
    }

    private function normalize(string $s): string
    {
        $t = preg_replace('/\s+/', ' ', strtoupper(trim($s)));
        $t = str_replace([' ST ', ' AVE ', ' RD '], [' STREET ', ' AVENUE ', ' ROAD '], ' ' + $t + ' ');

        return trim($t);
    }
}
