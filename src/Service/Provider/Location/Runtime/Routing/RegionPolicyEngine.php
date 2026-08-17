<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Routing;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Routing\RegionPolicyEngineInterface;

final class RegionPolicyEngine implements RegionPolicyEngineInterface
{
    /** @var list<array{when:array<string, mixed>, region:string, mode:string}> */
    private array $rule = [];

    /** @param array<string, mixed> $rule */
    public function set(array $rule): void
    {
        $when = [];
        $rawWhen = $rule['when'] ?? null;
        if (is_array($rawWhen)) {
            foreach ($rawWhen as $key => $value) {
                if (is_string($key)) {
                    $when[$key] = $value;
                }
            }
        }
        $region = is_string($rule['region'] ?? null) ? $rule['region'] : 'global';
        $mode = is_string($rule['mode'] ?? null) ? $rule['mode'] : 'allow';
        $this->rule[] = ['when' => $when, 'region' => $region, 'mode' => $mode];
    }

    /** @param array<string, mixed> $hint */
    public function decide(array $hint): string
    {
        foreach ($this->rule as $r) {
            $ok = true;
            $w = $r['when'];
            $expectedCountry = $w['country'] ?? null;
            $actualCountry = $hint['country'] ?? null;
            if (is_string($expectedCountry) && (!is_string($actualCountry) || $actualCountry !== $expectedCountry)) {
                $ok = false;
            }
            $bbox = $w['bbox'] ?? null;
            if ($ok && is_array($bbox) && 4 === count($bbox) && is_numeric($bbox[0] ?? null) && is_numeric($bbox[1] ?? null) && is_numeric($bbox[2] ?? null) && is_numeric($bbox[3] ?? null)) {
                $a = (float) $bbox[0];
                $b = (float) $bbox[1];
                $c = (float) $bbox[2];
                $d = (float) $bbox[3];
                $lat = is_numeric($hint['lat'] ?? null) ? (float) $hint['lat'] : 0.0;
                $lon = is_numeric($hint['lon'] ?? null) ? (float) $hint['lon'] : 0.0;
                $lat1 = min($a, $c);
                $lat2 = max($a, $c);
                $lon1 = min($b, $d);
                $lon2 = max($b, $d);
                if (!($lat >= $lat1 && $lat <= $lat2 && $lon >= $lon1 && $lon <= $lon2)) {
                    $ok = false;
                }
            }
            if ($ok) {
                return (string) $r['region'];
            }
        }

        return 'global';
    }
}
