<?php
declare(strict_types=1);
namespace Smartresponsor\Model\Locator;
final class AddressData{
  public function __construct(public string $street, public string $city, public string $region, public string $postalCode, public string $countryCode){}
  public function toArray(): array{ return ['street'=>$this->street,'city'=>$this->city,'region'=>$this->region,'postalCode'=>$this->postalCode,'countryCode'=>$this->countryCode]; }
  public static function fromArray(array $a): self{ return new self((string)($a['street']??''),(string)($a['city']??''),(string)($a['region']??''),(string)($a['postalCode']??''),(string)($a['countryCode']??'')); }
  public function oneLine(): string{ return trim($this->street.', '.$this->city.', '.$this->region.' '.$this->postalCode.', '.$this->countryCode); }
}
