<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Observability\Trace;
final class OtelZipkinExporter{
  public function __construct(private string $endpoint){}
  /** @param list<Span> $spans */
  public function export(string $service, array $spans): void{
    $payload=[];
    foreach($spans as $s){
      $tsUs = (int)round($s->start*1_000_000);
      $durUs = (int)round(($s->end - $s->start)*1_000_000);
      $tags=[]; foreach($s->attrs as $k=>$v){ $tags[]=['key'=>(string)$k, 'value'=>is_scalar($v)?(string)$v:json_encode($v)]; }
      $payload[]=[
        'traceId'=>$s->traceId,
        'id'=>$s->spanId,
        'parentId'=>$s->parentId,
        'name'=>$s->name,
        'timestamp'=>$tsUs,
        'duration'=>$durUs>0?$durUs:1,
        'localEndpoint'=>['serviceName'=>$service],
        'tags'=>array_reduce($tags,function($acc,$t){ $acc[$t['key']]=$t['value']; return $acc; },[])
      ];
    }
    $ch=curl_init($this->endpoint);
    curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>3,CURLOPT_POST=>1,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_POSTFIELDS=>json_encode($payload)]);
    curl_exec($ch); curl_close($ch);
  }
}
