<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
/**
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace App\Service;
class Geohash {
    private static string $base32 = '0123456789bcdefghjkmnpqrstuvwxyz';
    public static function encode(float $lat, float $lon, int $precision=7): string {
        $latInterval=[-90.0,90.0]; $lonInterval=[-180.0,180.0]; $hash=''; $isEven=true; $bit=0; $ch=0;
        $bits=[16,8,4,2,1];
        while(strlen($hash) < $precision){
            if($isEven){ $mid=array_sum($lonInterval)/2; if($lon > $mid){ $ch|=$bits[$bit]; $lonInterval[0]=$mid; } else { $lonInterval[1]=$mid; } }
            else { $mid=array_sum($latInterval)/2; if($lat > $mid){ $ch|=$bits[$bit]; $latInterval[0]=$mid; } else { $latInterval[1]=$mid; } }
            $isEven=!$isEven;
            if($bit < 4){ $bit++; } else { $hash .= self::$base32[$ch]; $bit=0; $ch=0; }
        }
        return $hash;
    }
    public static function haversine(float $lat1,float $lon1,float $lat2,float $lon2): float {
        $R=6371000.0; $dLat=deg2rad($lat2-$lat1); $dLon=deg2rad($lon2-$lon1);
        $a=sin($dLat/2)**2 + cos(deg2rad($lat1))*cos(deg2rad($lat2))*sin($dLon/2)**2;
        return 2*$R*asin(min(1.0, sqrt($a)));
    }
}
