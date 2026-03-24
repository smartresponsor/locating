<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Contract;
use App\Model\AddressData;
use App\Model\GeoPoint;
interface LocatorInterface{
  public function normalize(string $raw): AddressData;
  public function geocode(AddressData $a): GeoPoint;
  public function reverse(GeoPoint $p): AddressData;
}
