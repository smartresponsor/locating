<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Http;
final class SimpleHttp{
  public static function get(string $url, int $timeout=10): array{
    $ch=curl_init($url); curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>1,CURLOPT_TIMEOUT=>$timeout]); $b=curl_exec($ch); $c=(int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE); curl_close($ch);
    if($b===false||$c<200||$c>=300) return []; $d=json_decode((string)$b,true); return is_array($d)?$d:[];
  }
}
