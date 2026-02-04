<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Log;
final class Redactor{
  /** @return array<string,mixed> */
  public static function mask(array $ctx): array{
    foreach(['GOOGLE_API_KEY','USPS_USERID','Authorization','X-API-Key'] as $k){ if(isset($ctx[$k])) $ctx[$k]='***'; }
    return $ctx;
  }
}