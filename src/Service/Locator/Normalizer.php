<?php
declare(strict_types=1);
namespace SmartResponsor\Service\Locator;
use SmartResponsor\Model\Locator\CanonicalAddress;
final class Normalizer{
  public function canonicalize(array $raw, string $provider): array{
    $a = new CanonicalAddress(
      street: trim((string)($raw['street']??'')),
      house: trim((string)($raw['house']??'')),
      city: trim((string)($raw['city']??'')),
      region: trim((string)($raw['region']??'')),
      postalCode: trim((string)($raw['postalCode']??'')),
      countryCode: strtoupper(trim((string)($raw['countryCode']??''))),
      formatted: (string)($raw['formatted']??'')
    );
    $score = 0.0;
    if($a->street!=='') $score += 0.25;
    if($a->house!=='') $score += 0.15;
    if($a->city!=='') $score += 0.2;
    if($a->region!=='') $score += 0.1;
    if($a->postalCode!=='') $score += 0.15;
    if($a->countryCode!=='') $score += 0.15;
    if($provider==='mock') $score *= 0.7;
    $score = min(1.0, max(0.0, $score));
    return ['address'=>$a,'score'=>$score];
  }
}
