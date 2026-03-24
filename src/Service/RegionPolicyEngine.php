<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class RegionPolicyEngine implements RegionPolicyEngineInterface {
    /** @var array<int, array{when:array, region:string, mode:string}> */
    private array $rule = [];
    public function set(array $rule): void { $this->rule[] = ['when'=>$rule['when']??[], 'region'=>(string)($rule['region']??'global'), 'mode'=>(string)($rule['mode']??'allow')]; }
    public function decide(array $hint): string {
        foreach ($this->rule as $r) {
            $ok = true;
            $w = $r['when'];
            if (isset($w['country']) && (string)($hint['country']??'') !== (string)$w['country']) { $ok = false; }
            if ($ok && isset($w['bbox']) && is_array($w['bbox']) && count($w['bbox'])===4) {
                [$a,$b,$c,$d] = $w['bbox']; $lat=(float)($hint['lat']??0); $lon=(float)($hint['lon']??0);
                $lat1=min($a,$c); $lat2=max($a,$c); $lon1=min($b,$d); $lon2=max($b,$d);
                if (!($lat>=$lat1 && $lat<=$lat2 && $lon>=$lon1 && $lon<=$lon2)) { $ok=false; }
            }
            if ($ok) { return (string)$r['region']; }
        }
        return 'global';
    }
}
