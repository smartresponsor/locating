<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
namespace App\Integration\Http;
final class GoogleGeocodingClient{
  public function __construct(
    private string $baseUrl,
    private string $apiKey,
    private int $timeout = 10
  ){}
  /** @return array<mixed> */
  public function geocode(string $address): array{
    $params = ['address'=>$address, 'key'=>$this->apiKey];
    $url = $this->baseUrl . '?' . http_build_query($params);
    return $this->call($url);
  }
  /** @return array<mixed> */
  public function reverse(float $lat, float $lng): array{
    $params = ['latlng'=>sprintf('%.8f,%.8f',$lat,$lng), 'key'=>$this->apiKey];
    $url = $this->baseUrl . '?' . http_build_query($params);
    return $this->call($url);
  }
  /** @return array<mixed> */
  private function call(string $url): array{
    $ch = curl_init($url);
    curl_setopt_array($ch, [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_TIMEOUT => $this->timeout,
      CURLOPT_HTTPHEADER => ['Accept: application/json', 'User-Agent: App-Locator/1.0'],
    ]);
    $body = curl_exec($ch);
    if ($body === false) { $err=curl_error($ch); curl_close($ch); throw new \RuntimeException('HTTP: ' . $err); }
    $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE); curl_close($ch);
    if ($code < 200 || $code >= 300) { throw new \RuntimeException('HTTP status ' . $code); }
    $data = json_decode((string)$body, true);
    if (!is_array($data)) { throw new \RuntimeException('Invalid JSON'); }
    if (($data['status'] ?? '') !== 'OK') {
      $st = (string)($data['status'] ?? 'UNKNOWN'); $msg = (string)($data['error_message'] ?? '');
      throw new \RuntimeException('Google status ' . $st . ($msg?(': '+$msg):''));
    }
    return $data;
  }
}
