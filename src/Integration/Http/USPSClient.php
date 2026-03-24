<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Http;
final class USPSClient{
  public function __construct(
    private string $userId,
    private string $baseUrl = 'https://secure.shippingapis.com/ShippingAPI.dll',
    private int $timeout = 10
  ){
    if ($this->userId === ''){
      throw new \InvalidArgumentException('USPS userId is required');
    }
  }
  /** @return array{street:string,city:string,state:string,zip5:string,zip4:string} */
  public function verify(string $street, string $city, string $state, string $zip5 = '', string $zip4 = ''): array{
    $xml = '<AddressValidateRequest USERID="'.htmlspecialchars($this->userId, ENT_QUOTES).'"><Revision>1</Revision><Address ID="0"><Address1></Address1><Address2>'.htmlspecialchars($street, ENT_QUOTES).'</Address2><City>'.htmlspecialchars($city, ENT_QUOTES).'</City><State>'.htmlspecialchars($state, ENT_QUOTES).'</State><Zip5>'.htmlspecialchars($zip5, ENT_QUOTES).'</Zip5><Zip4>'.htmlspecialchars($zip4, ENT_QUOTES).'</Zip4></Address></AddressValidateRequest>';
    $url = $this->baseUrl . '?API=Verify&XML=' . rawurlencode($xml);
    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => $this->timeout,
      CURLOPT_HTTPHEADER => ['Accept: application/xml', 'User-Agent: App-Locator/1.0'],
    ]);
    $body = curl_exec($ch);
    if ($body === false){ $err = curl_error($ch); curl_close($ch); throw new \RuntimeException('USPS HTTP: ' . $err); }
    $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    curl_close($ch);
    if ($code < 200 || $code >= 300){ throw new \RuntimeException('USPS HTTP status ' . $code); }
    $sx = @simplexml_load_string((string)$body);
    if ($sx === false){ throw new \RuntimeException('USPS XML parse error'); }
    if (isset($sx->Number) || isset($sx->Description)){
      $msg = (string)($sx->Description ?? 'USPS error');
      throw new \RuntimeException('USPS: ' . $msg);
    }
    $addr = $sx->Address ?? null;
    $zip5 = (string)($addr->Zip5 ?? '');
    $zip4 = (string)($addr->Zip4 ?? '');
    $city = strtoupper((string)($addr->City ?? $city));
    $state = strtoupper((string)($addr->State ?? $state));
    $street = strtoupper(trim((string)($addr->Address2 ?? $street)));
    return ['street'=>$street,'city'=>$city,'state'=>$state,'zip5'=>$zip5,'zip4'=>$zip4];
  }
}
