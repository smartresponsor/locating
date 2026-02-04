<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Decorator;
use SmartResponsor\Contract\Locator\LocatorInterface;
use SmartResponsor\Model\Locator\AddressData;
use SmartResponsor\Model\Locator\GeoPoint;
final class RateLimitedLocator implements LocatorInterface{
  private float $bucket = 1.0;
  private int $last;
  private float $ratePerSec;
  public function __construct(private LocatorInterface $inner, int $perMinute = 120){
    $this->last = time();
    $this->ratePerSec = max(1, $perMinute) / 60.0;
  }
  private function gate(): void {
    $now = time();
    $this->bucket = min(1.0, $this->bucket + ($now - $this->last) * $this->ratePerSec);
    $this->last = $now;
    if ($this->bucket < 1.0) { usleep(250000); $this->bucket = 1.0; }
    $this->bucket -= 1.0;
  }
  public function normalize(string $raw): AddressData { $this->gate(); return $this->inner->normalize($raw); }
  public function geocode(AddressData $a): GeoPoint { $this->gate(); return $this->inner->geocode($a); }
  public function reverse(GeoPoint $p): AddressData { $this->gate(); return $this->inner->reverse($p); }
}
