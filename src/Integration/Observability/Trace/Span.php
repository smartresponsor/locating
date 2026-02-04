<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Observability\Trace;
final class Span{
  public function __construct(
    public string $traceId,
    public string $spanId,
    public ?string $parentId,
    public string $name,
    public float $start = 0.0,
    public float $end = 0.0,
    public int $status = 0,
    public array $attrs = []
  ){ $this->start = microtime(true); }
  public function end(int $status=0): void{ $this->status=$status; $this->end=microtime(true); }
  public function toArray(): array{
    return ['traceId'=>$this->traceId,'spanId'=>$this->spanId,'parentId'=>$this->parentId,'name'=>$this->name,
            'start'=>$this->start,'end'=>$this->end,'duration_ms'=>round(($this->end-$this->start)*1000,2),'status'=>$this->status,'attrs'=>$this->attrs];
  }
}
