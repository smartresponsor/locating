<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\Service;

use App\Infrastructure\Cache\RedisCache;
use App\ServiceInterface\LocationLocatorServiceInterface;

class LocationLocatorService implements LocationLocatorServiceInterface
{
    private RedisCache $cache;

    public function __construct(RedisCache $cache)
    {
        $this->cache = $cache;
    }

    public function search(?float $lat, ?float $lon, int $radiusMeters, string $bbox): array
    {
        $dataJson = $this->cache->get('store:data');
        if (!$dataJson) {
            $fn = __DIR__ . '/../../../data/stores.json';
            if (is_file($fn)) {
                $dataJson = file_get_contents($fn);
                $this->cache->set('store:data', $dataJson, 300);
            }
        }
        $items = $dataJson ? json_decode($dataJson, true) : [];
        $out = [];
        foreach ($items as $it) {
            if ($lat !== null && $lon !== null) {
                $d = Geohash::haversine($lat, $lon, $it['lat'], $it['lon']);
                if ($d <= $radiusMeters) {
                    $out[] = $it + ['distance' => $d];
                }
            } else {
                $out[] = $it;
            }
        }
        usort($out, static fn(array $a, array $b): int => ($a['distance'] ?? 0) <=> ($b['distance'] ?? 0));

        return array_slice($out, 0, 50);
    }
}
