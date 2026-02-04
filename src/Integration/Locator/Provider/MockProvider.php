<?php
declare(strict_types=1);
namespace SmartResponsor\Integration\Locator\Provider;
use SmartResponsor\Contract\Locator\ProviderInterface;
use SmartResponsor\Contract\Locator\ReverseProviderInterface;
final class MockProvider implements ProviderInterface, ReverseProviderInterface{
  public function name(): string{ return 'mock'; }
  public function geocode(string $q): array{
    $q=trim(strtolower($q)); $h=substr(sha1($q),0,8); $lat=(int)hexdec(substr($h,0,4))%90; $lon=(int)hexdec(substr($h,4,4))%180;
    return ['lat'=>$lat+0.1234,'lon'=>$lon+0.5678,'formatted'=>ucwords($q)];
  }
  public function reverse(float $lat, float $lon): array{
    return ['street'=>'Main St','house'=>strval(((int)abs($lat*10))%200+1),'city'=>'Testville','region'=>'Test State','postalCode'=>strval(((int)abs($lon*1000))%90000+10000),'countryCode'=>'US','formatted'=>'Main St, Testville'];
  }
}
