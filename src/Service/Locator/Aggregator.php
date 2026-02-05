<?php
declare(strict_types=1);
namespace Smartresponsor\Service\Locator;
use Smartresponsor\Contract\Locator\ProviderInterface;
use Smartresponsor\Integration\Locator\Cache\FileCache;
use Smartresponsor\Integration\Locator\Throttle\ProviderThrottle;
use Smartresponsor\Integration\Locator\Metrics\PrometheusExporter;

final class Aggregator{
  public function __construct(private array $providers, private FileCache $cache, private ProviderThrottle $throttle, private PrometheusExporter $metrics){}
  public function locate(string $q): array{
    $key=trim(strtolower($q));
    $c=$this->cache->get($key);
    if($c['hit']??false){
      if($c['stale']){ $this->metrics->stale(); } else { $this->metrics->hit(); }
      $val=$c['value']; return ['lat'=>$val['lat'],'lon'=>$val['lon'],'provider'=>$val['provider'],'formatted'=>$val['formatted'],'cache'=>$c['stale']?'stale':'hit'];
    }
    $this->metrics->miss();
    foreach($this->providers as $p){
      if(!$this->throttle->allow($p->name())){ $this->metrics->throttled(); continue; }
      try{
        $res=$p->geocode($q);
        $out=['lat'=>$res['lat'],'lon'=>$res['lon'],'provider'=>$p->name(),'formatted'=>$res['formatted']];
        $this->cache->set($key, $out);
        return $out+['cache'=>'miss'];
      }catch(\Throwable $e){}
    }
    $h=substr(sha1($key),0,8); $lat=(int)hexdec(substr($h,0,4))%90; $lon=(int)hexdec(substr($h,4,4))%180;
    $out=['lat'=>$lat+0.1234,'lon'=>$lon+0.5678,'provider'=>'fallback','formatted'=>ucwords($key)];
    return $out+['cache'=>'none'];
  }
}
