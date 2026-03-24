<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Contract;
use Smartresponsor\Model\AddressData;
use Smartresponsor\Model\GeoPoint;
interface LocatorInterface{
  public function normalize(string $raw): AddressData;
  public function geocode(AddressData $a): GeoPoint;
  public function reverse(GeoPoint $p): AddressData;
}
