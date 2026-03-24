<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Contract\RateLimit;
interface RateLimiterInterface{
  /** Reserve one token; returns seconds to wait if not allowed (0 means allowed immediately). */
  public function consume(string $bucket, int $perMinute): int;
}
