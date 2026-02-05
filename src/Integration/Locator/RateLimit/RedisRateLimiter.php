<?php
declare(strict_types=1);
namespace Smartresponsor\Integration\Locator\RateLimit;
use Redis;
use Smartresponsor\Contract\RateLimit\RateLimiterInterface;
final class RedisRateLimiter implements RateLimiterInterface{
  public function __construct(private Redis $r){}
  public function consume(string $bucket, int $perMinute): int{
    $key = 'rl:' . $bucket . ':' . (int)floor(time()/60);
    $count = (int)$this->r->incr($key);
    if ($count === 1){ $this->r->expire($key, 60); }
    if ($count > $perMinute){
      $ttl = (int)$this->r->ttl($key);
      return $ttl < 0 ? 60 : $ttl;
    }
    return 0;
  }
}
