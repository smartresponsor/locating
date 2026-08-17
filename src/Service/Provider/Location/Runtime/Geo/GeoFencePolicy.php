<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Geo;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Geo\GeoFencePolicyInterface;

final class GeoFencePolicy implements GeoFencePolicyInterface
{
    /** @var array<int,array{nameEntity:string,region:string,minLat:float,minLon:float,maxLat:float,maxLon:float}> */
    private array $box = [];

    public function add(string $nameEntity, string $region, float $minLat, float $minLon, float $maxLat, float $maxLon): void
    {
        $this->box[] = ['nameEntity' => $nameEntity, 'region' => $region, 'minLat' => $minLat, 'minLon' => $minLon, 'maxLat' => $maxLat, 'maxLon' => $maxLon];
    }

    public function decide(float $lat, float $lon, string $defaultRegion = 'us'): string
    {
        foreach ($this->box as $b) {
            if ($lat >= $b['minLat'] && $lat <= $b['maxLat'] && $lon >= $b['minLon'] && $lon <= $b['maxLon']) {
                return $b['region'];
            }
        }

        return $defaultRegion;
    }

    public function allow(float $lat, float $lon, string $region): bool
    {
        foreach ($this->box as $b) {
            if ($b['region'] !== $region) {
                continue;
            }
            if ($lat >= $b['minLat'] && $lat <= $b['maxLat'] && $lon >= $b['minLon'] && $lon <= $b['maxLon']) {
                return true;
            }
        }

        return false;
    }
}
