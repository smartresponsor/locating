<?php
declare(strict_types=1);
namespace Smartresponsor\Contract\RateLimit;
interface RateLimiterInterface{
  /** Reserve one token; returns seconds to wait if not allowed (0 means allowed immediately). */
  public function consume(string $bucket, int $perMinute): int;
}
