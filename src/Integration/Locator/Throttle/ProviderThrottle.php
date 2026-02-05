<?php
declare(strict_types=1);
namespace Smartresponsor\Integration\Locator\Throttle;
final class ProviderThrottle{
  private string $dir; private array $cfg=[];
  public function __construct(string $spec){
    $this->dir=sys_get_temp_dir().'/locator_throttle'; if(!is_dir($this->dir)){ @mkdir($this->dir,0755,true); }
    foreach(array_filter(array_map('trim', explode(',', $spec))) as $p){
      [$prov,$lim] = array_pad(explode(':',$p,2),2,'');
      if(!$prov || !$lim) continue; [$c,$s] = array_map('intval', explode('/',$lim,2));
      $this->cfg[$prov]=['limit'=>$c,'window'=>$s];
    }
  }
  private function path(string $prov): string{ return $this->dir.'/'.sha1($prov).'.json'; }
  public function allow(string $prov): bool{
    $c=$this->cfg[$prov] ?? ['limit'=>100,'window'=>1];
    $p=$this->path($prov); $now=time();
    $state=['ts'=>$now,'cnt'=>0];
    if(file_exists($p)){ $state=json_decode((string)file_get_contents($p),true)?:$state; }
    if($now - (int)$state['ts'] >= $c['window']){ $state=['ts'=>$now,'cnt'=>0]; }
    $ok = $state['cnt'] < $c['limit']; if($ok){ $state['cnt']+=1; file_put_contents($p, json_encode($state)); }
    return $ok;
  }
}
