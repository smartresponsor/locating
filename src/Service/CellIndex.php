<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service;
final class CellIndex implements CellIndexInterface {
    public function toCell(float $lat, float $lon, int $level): string {
        $lvl = max(0, min(15, $level));
        $n = 1<<$lvl;
        $x = (int)floor(($lon + 180.0)/360.0 * $n);
        $y = (int)floor(($lat + 90.0)/180.0 * $n);
        $x = max(0, min($n-1, $x)); $y = max(0, min($n-1, $y));
        return 'L'.$lvl.':'.$x.':'.$y;
    }
    public function neighbor(string $cellId): array {
        [$lvl,$x,$y] = $this->parse($cellId);
        $n = 1<<$lvl;
        $N = 'L'.$lvl.':'.$x.':'.max(0,$y-1);
        $S = 'L'.$lvl.':'.$x.':'.min($n-1,$y+1);
        $W = 'L'.$lvl.':'.max(0,$x-1).':'.$y;
        $E = 'L'.$lvl.':'.min($n-1,$x+1).':'.$y;
        return ['N'=>$N,'E'=>$E,'S'=>$S,'W'=>$W];
    }
    public function cover(array $bbox, int $level): array {
        $lvl = max(0, min(15, $level));
        $n = 1<<$lvl;
        $lat1 = min((float)$bbox[0], (float)$bbox[2]); $lon1 = min((float)$bbox[1], (float)$bbox[3]);
        $lat2 = max((float)$bbox[0], (float)$bbox[2]); $lon2 = max((float)$bbox[1], (float)$bbox[3]);
        $x1=(int)floor(($lon1 + 180.0)/360.0 * $n); $x2=(int)floor(($lon2 + 180.0)/360.0 * $n);
        $y1=(int)floor(($lat1 + 90.0)/180.0 * $n); $y2=(int)floor(($lat2 + 90.0)/180.0 * $n);
        $out=[];
        for($y=max(0,$y1); $y<=min($n-1,$y2); $y++){
            for($x=max(0,$x1); $x<=min($n-1,$x2); $x++){
                $out[]='L'.$lvl.':'.$x.':'.$y;
            }
        }
        return $out;
    }
    private function parse(string $id): array {
        if (!preg_match('/^L(\d+):(\d+):(\d+)$/', $id, $m)) { throw new \InvalidArgumentException('Bad cell id'); }
        return [ (int)$m[1], (int)$m[2], (int)$m[3] ];
    }
}
