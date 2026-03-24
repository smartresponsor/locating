<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Provider;
use Smartresponsor\Contract\ProviderInterface;
final class PhotonProvider implements ProviderInterface{
  public function __construct(private string $baseUrl){}
  public function name(): string{ return 'photon'; }
  public function geocode(string $q): array{
    $url=rtrim($this->baseUrl,'/').'/api?q='.rawurlencode($q).'&limit=1';
    $ch=curl_init($url); curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>4,CURLOPT_USERAGENT=>'locator-phase-41']); $body=curl_exec($ch); $code=(int)curl_getinfo($ch,CURLINFO_RESPONSE_CODE); curl_close($ch);
    if($code>=200 && $code<300 && $body){
      $j=json_decode((string)$body,true)?:[]; $f=$j['features'][0]??null;
      if($f && isset($f['geometry']['coordinates'])){ $c=$f['geometry']['coordinates']; return ['lat'=>(float)$c[1],'lon'=>(float)$c[0],'formatted'=>$f['properties']['name']??$q]; }
    }
    throw new \RuntimeException('geocode_failed');
  }
}
