<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace SmartResponsor\Service\Locator\Locator;
use SmartResponsor\Infrastructure\Locator\Cache\RedisCache;
use SmartResponsor\ServiceInterface\Locator\Locator\LocatorServiceInterface;
class LocatorService implements LocatorServiceInterface {
    private RedisCache $cache;
    public function __construct(RedisCache $cache){ $this->cache=$cache; }
    public function search(?float $lat, ?float $lon, int $radiusMeters, string $bbox): array {
        $dataJson = $this->cache->get('store:data');
        if(!$dataJson){
            $fn=__DIR__.'/../../../data/stores.json';
            if(is_file($fn)){ $dataJson=file_get_contents($fn); $this->cache->set('store:data',$dataJson,300); }
        }
        $items = $dataJson ? json_decode($dataJson,true) : [];
        $out=[];
        foreach($items as $it){
            if($lat!==null && $lon!==null){
                $d = Geohash::haversine($lat,$lon,$it['lat'],$it['lon']);
                if($d <= $radiusMeters) $out[]=$it+['distance'=>$d];
            } else {
                $out[]=$it;
            }
        }
        usort($out, function($a,$b){ return ($a['distance']??0) <=> ($b['distance']??0); });
        return array_slice($out, 0, 50);
    }
}
