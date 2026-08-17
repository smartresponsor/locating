<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */

namespace App\Locating\Service\Provider\Location\Runtime\Geo;

use App\Locating\Infrastructure\Provider\Location\Cache\RedisCache;
use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\LocatorServiceInterface;

class LocatorService implements LocatorServiceInterface
{
    private RedisCache $cache;
    public function __construct(RedisCache $cache)
    {
        $this->cache = $cache;
    }
    /** @return list<array<string, mixed>> */
    public function search(?float $lat, ?float $lon, int $radiusMeters, string $bbox): array
    {
        $dataJson = $this->cache->get('store:data');
        if (null === $dataJson || '' === $dataJson) {
            $fn = __DIR__.'/../../../data/stores.json';
            if (is_file($fn)) {
                $contents = file_get_contents($fn);
                if (is_string($contents)) {
                    $dataJson = $contents;
                    $this->cache->set('store:data', $dataJson, 300);
                }
            }
        }
        $decoded = is_string($dataJson) && '' !== $dataJson ? json_decode($dataJson, true) : [];
        $items = is_array($decoded) ? $decoded : [];
        $out = [];
        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }
            $row = [];
            foreach ($item as $key => $value) {
                if (is_string($key)) {
                    $row[$key] = $value;
                }
            }
            if (null !== $lat && null !== $lon) {
                $itemLat = $row['lat'] ?? null;
                $itemLon = $row['lon'] ?? null;
                if (!is_numeric($itemLat) || !is_numeric($itemLon)) {
                    continue;
                }
                $distance = Geohash::haversine($lat, $lon, (float) $itemLat, (float) $itemLon);
                if ($distance <= $radiusMeters) {
                    $row['distance'] = $distance;
                    $out[] = $row;
                }
            } else {
                $out[] = $row;
            }
        }
        usort($out, static function (array $left, array $right): int {
            $leftDistance = is_numeric($left['distance'] ?? null) ? (float) $left['distance'] : 0.0;
            $rightDistance = is_numeric($right['distance'] ?? null) ? (float) $right['distance'] : 0.0;

            return $leftDistance <=> $rightDistance;
        });

        return array_slice($out, 0, 50);
    }
}
