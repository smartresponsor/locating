<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class GeoFencePolicy implements GeoFencePolicyInterface {
    /** @var array<int, array{name:string,region:string,minLat:float,minLon:float,maxLat:float,maxLon:float}> */
    private array $box = [];
    public function add(string $name, string $region, float $minLat, float $minLon, float $maxLat, float $maxLon): void {
        $this->box[] = ['name'=>$name,'region'=>$region,'minLat'=>$minLat,'minLon'=>$minLon,'maxLat'=>$maxLat,'maxLon'=>$maxLon];
    }
    public function decide(float $lat, float $lon, string $defaultRegion='us'): string {
        foreach ($this->box as $b) {
            if ($lat >= $b['minLat'] && $lat <= $b['maxLat'] && $lon >= $b['minLon'] && $lon <= $b['maxLon']) {
                return $b['region'];
            }
        }
        return $defaultRegion;
    }
    public function allow(float $lat, float $lon, string $region): bool {
        foreach ($this->box as $b) {
            if ($b['region'] !== $region) { continue; }
            if ($lat >= $b['minLat'] && $lat <= $b['maxLat'] && $lon >= $b['minLon'] && $lon <= $b['maxLon']) { return true; }
        }
        return false;
    }
}
