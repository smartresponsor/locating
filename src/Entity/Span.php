<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Entity;
final class Span {
    private float $start; private float $end=0.0;
    public function __construct(private string $name, private TraceContext $ctx){
        $this->start = microtime(true);
    }
    public function end(): void { $this->end = microtime(true); }
    public function export(array $attr=[]): array {
        $dur = ($this->end>0.0) ? ($this->end - $this->start) : 0.0;
        return ['name'=>$this->name,'trace'=>$this->ctx->traceId,'span'=>$this->ctx->spanId,'dur_ms'=>(int)round($dur*1000),'attr'=>$attr];
    }
}
