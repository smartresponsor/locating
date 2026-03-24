<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Service;
final class HealthMonitor{
  private string $path;
  private array $state=[];
  public function __construct(?string $file=null){
    $this->path=$file ?: sys_get_temp_dir().'/locator_health.json';
    if(is_file($this->path)){ $d=json_decode((string)file_get_contents($this->path),true); if(is_array($d)) $this->state=$d; }
  }
  public function update(string $provider, bool $ok, float $ms): void{
    if(!isset($this->state[$provider])){ $this->state[$provider]=['ok'=>0,'fail'=>0,'ewma_ms'=>500.0,'last'=>0]; }
    $alpha=0.3; $cur=$this->state[$provider];
    $cur['ewma_ms'] = $alpha*$ms + (1-$alpha)*$cur['ewma_ms'];
    if($ok){ $cur['ok']++; } else { $cur['fail']++; }
    $cur['last']=time(); $this->state[$provider]=$cur; $this->flush();
  }
  private function flush(): void{ @file_put_contents($this->path, json_encode($this->state)); }
  public function snapshot(): array{
    $out=[];
    foreach($this->state as $p=>$s){
      $total=max(1, $s['ok']+$s['fail']);
      $sr=$s['ok']/$total; $score = (int)round($sr*100 - min(1000.0, $s['ewma_ms']));
      $out[$p]=['ok'=>$s['ok'],'fail'=>$s['fail'],'successRate'=>$sr,'ewmaMs'=>$s['ewma_ms'],'score'=>$score,'last'=>$s['last']];
    }
    return $out;
  }
}
