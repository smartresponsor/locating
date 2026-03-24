<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Log\Correlation;
final class TraceContext{
  private static ?string $traceId=null;
  private static ?string $spanId=null;
  public static function start(?string $traceId=null, ?string $parentSpanId=null): void{
    self::$traceId = $traceId ?? bin2hex(random_bytes(16));
    self::$spanId = $parentSpanId ?? bin2hex(random_bytes(8));
  }
  public static function child(): void{
    if (!self::$traceId){ self::start(); }
    self::$spanId = bin2hex(random_bytes(8));
  }
  public static function traceparent(): string{
    $tid = self::$traceId ?? bin2hex(random_bytes(16));
    $sid = self::$spanId ?? bin2hex(random_bytes(8));
    return sprintf('00-%s-%s-01', $tid, $sid);
  }
  public static function getTraceId(): ?string{ return self::$traceId; }
  public static function getSpanId(): ?string{ return self::$spanId; }
}
