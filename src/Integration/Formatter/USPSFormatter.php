<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace Smartresponsor\Integration\Formatter;
use Smartresponsor\Model\AddressData;
final class USPSFormatter{
  public static function formatZip(string $zip5, string $zip4): string{
    return $zip4 !== '' ? ($zip5 . '-' . $zip4) : $zip5;
  }
  public static function toAddress(array $verified): AddressData{
    return new AddressData(
      $verified['street'] ?? '',
      $verified['city'] ?? '',
      $verified['state'] ?? '',
      self::formatZip($verified['zip5'] ?? '', $verified['zip4'] ?? ''),
      'US'
    );
  }
}
