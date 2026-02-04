<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\RateLimit;
/**
 * File-backed sliding window (per key). Not for HA, но достаточно для demo.
 */
final class RateLimiter{
  private string $dir;
  public function __construct(private int $limit, private int $windowSec, private int $burst){
    $this->dir = sys_get_temp_dir().'/locator_rl';
    if(!is_dir($this->dir)){ @mkdir($this->dir, 0755, true); }
  }
  private function path(string $key): string{ return $this->dir.'/'.sha1($key).'.json'; }
  /** @return array{allowed:bool, remaining:int, reset:int} */
  public function allow(string $key, int $now=null): array{
    $now = $now ?? time();
    $p=$this->path($key);
    $state=['ts'=>$now,'tokens'=>$this->limit + $this->burst];
    if(file_exists($p)){
      $state = json_decode((string)file_get_contents($p), true) ?: $state;
      $elapsed = max(0, $now - (int)$state['ts']);
      $refill = (int)floor($elapsed * ($this->limit / $this->windowSec));
      $state['tokens'] = min($this->limit + $this->burst, (int)$state['tokens'] + $refill);
      $state['ts']=$now;
    }
    $allowed = false;
    if($state['tokens']>0){ $state['tokens']-=1; $allowed=true; }
    file_put_contents($p, json_encode($state));
    $reset = $this->windowSec - (($now - ((int)$state['ts']-$elapsed if isset($elapsed) else $now)) % $this->windowSec);
    return ['allowed'=>$allowed, 'remaining'=>(int)$state['tokens'], 'reset'=>max(1,$reset)];
  }
}
