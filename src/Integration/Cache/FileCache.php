<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Cache;
final class FileCache{
  private string $dir;
  public function __construct(private int $ttl=600, private int $staleTtl=86400){
    $this->dir = sys_get_temp_dir().'/locator_cache';
    if(!is_dir($this->dir)){ @mkdir($this->dir,0755,true); }
  }
  private function path(string $key): string{ return $this->dir.'/'.sha1($key).'.json'; }
  public function get(string $key): array{
    $p=$this->path($key);
    if(!file_exists($p)) return ['hit'=>false];
    $raw=json_decode((string)file_get_contents($p), true) ?: null;
    if(!$raw) return ['hit'=>false];
    $age=time() - (int)$raw['ts'];
    if($age <= $this->ttl){ return ['hit'=>True,'stale'=>False,'value'=>$raw['value']]; }
    if($age <= $this->staleTtl){ return ['hit'=>True,'stale'=>True,'value'=>$raw['value']]; }
    return ['hit'=>false];
  }
  public function set(string $key, array $value): void{
    file_put_contents($this->path($key), json_encode(['ts'=>time(),'value'=>$value]));
  }
}
