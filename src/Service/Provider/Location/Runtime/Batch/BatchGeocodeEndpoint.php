<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Batch;

use App\Locating\InfrastructureInterface\Provider\Location\Cache\ResultCacheInterface;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Batch\BatchGeocodeEndpointInterface;

final class BatchGeocodeEndpoint implements BatchGeocodeEndpointInterface
{
    public function __construct(private ResultCacheInterface $cache)
    {
    }

    /**
     * @param list<string|array{text?:string}> $input
     * @return array<int, array<string, mixed>>
     */
    public function handle(array $input): array
    {
        $out = [];
        foreach ($input as $i => $raw) {
            $text = is_array($raw) ? ($raw['text'] ?? '') : $raw;
            $key = $this->key($text);
            $hit = $this->cache->get($key);
            if (null !== $hit) {
                $out[$i] = $hit;
                continue;
            }
            $norm = $this->normalize($text);
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
        $t = preg_replace('/\s+/', ' ', strtoupper(trim($s))) ?? strtoupper(trim($s));
        $t = str_replace([' ST ', ' AVE ', ' RD '], [' STREET ', ' AVENUE ', ' ROAD '], ' '.$t.' ');

        return trim($t);
    }
}
