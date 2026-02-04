<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Observability\Trace;
final class Tracer{
  /** @var list<Span> */ private array $spans=[];
  private ?string $file; private ?string $httpUrl; private ?OtelZipkinExporter $zipkin=null;
  public function __construct(private string $service='locator', private string $export='console'){
    $this->file = str_starts_with($export,'file:') ? substr($export,5) : null;
    $this->httpUrl = (str_starts_with($export,'http:')||str_starts_with($export,'https:')) && !str_starts_with($export,'zipkin:') ? $export : null;
    if(str_starts_with($export,'zipkin:')){ $this->zipkin = new OtelZipkinExporter(substr($export,7)); }
    if($this->file){ @mkdir(dirname($this->file),0755,true); }
  }
  public static function parseTraceParent(?string $h): array{
    if(!$h) return ['traceId'=>bin2hex(random_bytes(16)),'parentId'=>null];
    $p=explode('-',trim($h)); if(count($p)>=4){ return ['traceId'=>$p[1], 'parentId'=>$p[2]]; }
    return ['traceId'=>bin2hex(random_bytes(16)),'parentId'=>null];
  }
  public function startSpan(string $name, ?string $traceId=null, ?string $parentId=null): Span{
    $traceId = $traceId ?? bin2hex(random_bytes(16)); $spanId = bin2hex(random_bytes(8));
    $sp = new Span($traceId, $spanId, $parentId, $name); $this->spans[]=$sp; return $sp;
  }
  public function export(): void{
    if(empty($this->spans)) return;
    if($this->zipkin){ $this->zipkin->export($this->service, $this->spans); $this->spans=[]; return; }
    $payload = ['service'=>$this->service,'spans'=>array_map(fn($s)=>$s->toArray(), $this->spans)];
    if($this->file){ file_put_contents($this->file, json_encode($payload,JSON_UNESCAPED_UNICODE)."\n", FILE_APPEND); }
    elseif($this->httpUrl){
      $ch=curl_init($this->httpUrl); curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>3,CURLOPT_POST=>1,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_POSTFIELDS=>json_encode($payload)]); curl_exec($ch); curl_close($ch);
    } else { error_log(json_encode($payload, JSON_UNESCAPED_UNICODE)); }
    $this->spans=[];
  }
}
