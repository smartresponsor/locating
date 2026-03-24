<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Model;
final class GeoPoint{
  public function __construct(public float $latitude, public float $longitude){}
  /** @return array{lat:float,lon:float} */
  public function toArray(): array{ return ['lat'=>$this->latitude,'lon'=>$this->longitude]; }
  public function isZero(): bool{ return $this->latitude==0.0 && $this->longitude==0.0; }
}
