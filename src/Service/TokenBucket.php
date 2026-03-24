<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Service;
final class TokenBucket{
  private string $file;
  private int $capacity;
  private float $rate;
  public function __construct(string $name, int $capacity=10, float $rate=1.0){
    $this->file=sys_get_temp_dir().'/sr_tb_'.preg_replace('/[^a-z0-9_\-]/i','_',$name).'.json';
    $this->capacity=$capacity; $this->rate=$rate;
  }
  public function allow(): bool{
    $now=microtime(true);
    $state=['tokens'=>$this->capacity,'ts'=>$now];
    if(is_file($this->file)){ $state=json_decode((string)file_get_contents($this->file),true)?:$state; }
    $elapsed=max(0.0,$now-($state['ts']??$now)); $state['tokens']=min($this->capacity, ($state['tokens']??0)+$elapsed*$this->rate); $state['ts']=$now;
    if($state['tokens']<1.0){ file_put_contents($this->file,json_encode($state)); return false; }
    $state['tokens']-=1.0; file_put_contents($this->file,json_encode($state)); return true;
  }
}
