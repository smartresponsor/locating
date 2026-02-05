<?php
declare(strict_types=1);
namespace Smartresponsor\Integration\Locator\Cache;
final class SimpleArrayCache implements CacheInterface{
  /** @var array<string,array{v:mixed,exp:int}> */
  private array $data=[];
  private int $hits=0; private int $misses=0;
  public function __construct(private int $max=100){}
  public function get(string $key): mixed{
    if(!isset($this->data[$key])){ $this->misses++; return null; }
    $e=$this->data[$key];
    if($e['exp']<time()){ unset($this->data[$key]); $this->misses++; return null; }
    $this->hits++; return $e['v'];
  }
  public function set(string $key, mixed $value, int $ttlSec): void{
    if(count($this->data) >= $this->max){ array_shift($this->data); }
    $this->data[$key]=['v'=>$value,'exp'=>time()+$ttlSec];
  }
  public function delete(string $key): void{ unset($this->data[$key]); }
  public function clear(): void{ $this->data=[]; $this->hits=$this->misses=0; }
  public function stats(): array{ return ['items'=>count($this->data),'capacity'=>$this->max,'hits'=>$this->hits,'misses'=>$this->misses]; }
}
