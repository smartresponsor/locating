<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Provider;
use SmartResponsor\Contract\Locator\ProviderInterface;
use SmartResponsor\Contract\Locator\ReverseProviderInterface;
final class NominatimProvider implements ProviderInterface, ReverseProviderInterface{
  public function __construct(private string $baseUrl){}
  public function name(): string{ return 'nominatim'; }
  public function geocode(string $q): array{
    $url = rtrim($this->baseUrl,'/').'/search?format=json&limit=1&q='.rawurlencode($q);
    $ch=curl_init($url); curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>4,CURLOPT_USERAGENT=>'locator-phase-38']); $body=curl_exec($ch); $code=(int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE); curl_close($ch);
    if($code>=200 && $code<300 && $body){ $arr=json_decode((string)$body,true); if(is_array($arr) && isset($arr[0])){ return ['lat'=>(float)$arr[0]['lat'],'lon'=>(float)$arr[0]['lon'],'formatted'=>(string)($arr[0]['display_name']??$q)]; } }
    throw new \RuntimeException('geocode_failed');
  }
  public function reverse(float $lat, float $lon): array{
    $url = rtrim($this->baseUrl,'/').'/reverse?format=json&lat='.rawurlencode((string)$lat).'&lon='.rawurlencode((string)$lon);
    $ch=curl_init($url); curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>4,CURLOPT_USERAGENT=>'locator-phase-38']); $body=curl_exec($ch); $code=(int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE); curl_close($ch);
    if($code>=200 && $code<300 && $body){
      $j=json_decode((string)$body,true)?:[]; $addr=$j['address']??[];
      return ['street'=>$addr['road']??($addr['pedestrian']??($addr['footway']??'')),'house'=>$addr['house_number']??'','city'=>$addr['city']??($addr['town']??($addr['village']??'')),'region'=>$addr['state']??'','postalCode'=>$addr['postcode']??'','countryCode'=>strtoupper((string)($addr['country_code']??'')),'formatted'=>$j['display_name']??''];
    }
    throw new \RuntimeException('reverse_failed');
  }
}
