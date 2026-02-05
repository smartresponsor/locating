<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Entity\Locator;
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
