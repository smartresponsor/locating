<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Batch;
final class JobStore{
  private string $dir;
  public function __construct(){
    $this->dir= sys_get_temp_dir().'/locator_jobs';
    if(!is_dir($this->dir)){ @mkdir($this->dir,0755,true); }
  }
  private function path(string $id): string{ return $this->dir.'/'.preg_replace('/[^a-zA-Z0-9_-]/','_',$id).'.json'; }
  private function newId(): string{ return bin2hex(random_bytes(12)); }
  public function create(array $queries, ?string $idemKey=null): string{
    $id = $idemKey ? 'idem_'.substr(hash('sha256', json_encode($queries).$idemKey),0,24) : $this->newId();
    $p=$this->path($id);
    if(file_exists($p)){ return $id; }
    $payload=['id'=>$id,'status'=>'created','total'=>count($queries),'done'=>0,'items'=>array_map(fn($q)=>['q'=>$q], $queries),'created_at'=>date('c'),'started_at'=>null,'finished_at'=>null];
    file_put_contents($p, json_encode($payload));
    return $id;
  }
  public function read(string $id): ?array{
    $p=$this->path($id); if(!file_exists($p)) return null;
    return json_decode((string)file_get_contents($p), true) ?: null;
  }
  public function write(array $job): void{
    $p=$this->path((string)$job['id']); file_put_contents($p, json_encode($job));
  }
  /** @return list<string> */
  public function listByStatus(string $status): array{
    $out=[]; foreach(glob($this->dir+'/*.json')?:[] as $f){
      $j=json_decode((string)file_get_contents($f), true); if(!$j) continue;
      if(($j['status']??'')===$status){ $out[]=basename($f, '.json'); }
    } return $out;
  }
}
