<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace SmartResponsor\Service\Locator\Provider;
use SmartResponsor\Domain\Locator\Config\Env;
use SmartResponsor\Infrastructure\Locator\Http\HttpClient;
use SmartResponsor\ServiceInterface\Locator\Provider\GeocodeProviderInterface;
class NominatimProvider implements GeocodeProviderInterface{
    private Env $env; private HttpClient $http;
    public function __construct(Env $env){$this->env=$env; $this->http=new HttpClient();}
    public function name(): string { return 'nominatim'; }
    public function geocode(string $q,string $country): array{
        $base=rtrim($this->env->get('NOMINATIM_URL','https://nominatim.openstreetmap.org'),'/');
        $url=$base.'/search?format=jsonv2&q='.rawurlencode($q).'&limit=5&addressdetails=1';
        if($country)$url.='&countrycodes='.rawurlencode(strtolower($country));
        [$code,$body]=$this->http->get($url, ['User-Agent'=>'SmartResponsor-Locator/1.0']); if($code!==200) return [];
        $d=json_decode($body,true); $out=[];
        foreach(($d??[]) as $it){
            $out[]=[ 'formatted'=>$it['display_name'] ?? '', 'lat'=>isset($it['lat'])?(float)$it['lat']:null,
                     'lon'=>isset($it['lon'])?(float)$it['lon']:null, 'source'=>'nominatim', 'confidence'=>(float)($it['importance'] ?? 0.4) ];
        } return $out;
    }
    public function reverse(float $lat,float $lon): array{
        $base=rtrim($this->env->get('NOMINATIM_URL','https://nominatim.openstreetmap.org'),'/');
        $url=$base.'/reverse?format=jsonv2&lat='.$lat.'&lon='.$lon;
        [$code,$body]=$this->http->get($url, ['User-Agent'=>'SmartResponsor-Locator/1.0']); if($code!==200) return [];
        $d=json_decode($body,true); if(!is_array($d)) return [];
        return [[ 'formatted'=>$d['display_name'] ?? '', 'lat'=>$lat, 'lon'=>$lon, 'source'=>'nominatim', 'confidence'=>0.5 ]];
    }
    public function autocomplete(string $q,string $country,string $bbox): array{
        $res=$this->geocode($q,$country); $out=[];
        foreach($res as $r){
            $out[]=[ 'text'=>$r['formatted'] ?? '', 'placeId'=>md5(($r['formatted']??'').'|'.($r['lat']??'').'|'.($r['lon']??'')),
                     'lat'=>$r['lat'] ?? null, 'lon'=>$r['lon'] ?? null, 'score'=>$r['confidence'] ?? 0.5 ];
        } return $out;
    }
}
