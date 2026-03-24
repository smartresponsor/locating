<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Decorator;
use Smartresponsor\Contract\LocatorInterface;
use Smartresponsor\Model\AddressData;
use Smartresponsor\Model\GeoPoint;
final class TryFallbackLocator implements LocatorInterface{
  public function __construct(private LocatorInterface $primary, private LocatorInterface $secondary){}
  private function notEmpty(AddressData $a): bool { return !$a->isEmpty(); }
  private function notZero(GeoPoint $p): bool { return !$p->isZero(); }
  public function normalize(string $raw): AddressData{
    try{ $r=$this->primary->normalize($raw); if($this->notEmpty($r)) return $r; }catch(\Throwable $e){}
    return $this->secondary->normalize($raw);
  }
  public function geocode(AddressData $a): GeoPoint{
    try{ $p=$this->primary->geocode($a); if($this->notZero($p)) return $p; }catch(\Throwable $e){}
    return $this->secondary->geocode($a);
  }
  public function reverse(GeoPoint $p): AddressData{
    try{ $a=$this->primary->reverse($p); if($this->notEmpty($a)) return $a; }catch(\Throwable $e){}
    return $this->secondary->reverse($p);
  }
}
