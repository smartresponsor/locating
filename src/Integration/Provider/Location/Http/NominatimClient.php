<?php

declare(strict_types=1);

namespace App\Locating\Integration\Provider\Location\Http;

final class NominatimClient
{
    public function __construct(
        private string $baseUrl = 'https://nominatim.openstreetmap.org',
        private ?string $email = null,
        private int $timeout = 10
    ) {
    }
    /** @return array<mixed> */
    public function search(string $q, int $limit = 1): array
    {
        $params = [
          'format' => 'jsonv2', 'q' => $q, 'addressdetails' => '1', 'limit' => (string)$limit,
        ];
        if ($this->email) {
            $params['email'] = $this->email;
        }
        $url = $this->baseUrl . '/search?' . http_build_query($params);
        return $this->getJson($url);
    }
    /** @return array<mixed> */
    public function reverse(float $lat, float $lon): array
    {
        $params = [
          'format' => 'jsonv2', 'lat' => (string)$lat, 'lon' => (string)$lon, 'addressdetails' => '1',
        ];
        if ($this->email) {
            $params['email'] = $this->email;
        }
        $url = $this->baseUrl . '/reverse?' . http_build_query($params);
        return $this->getJson($url);
    }
    public function ping(): bool
    {
        $ch = curl_init($this->baseUrl.'/');
        curl_setopt_array($ch, [
            CURLOPT_NOBODY => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => min(3, $this->timeout),
            CURLOPT_HTTPHEADER => [
                'User-Agent: Smartresponsor-Locator/1.0 (+https://example.local)',
            ],
        ]);
        $result = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);

        return false !== $result && $code >= 200 && $code < 500;
    }

    /** @return array<mixed> */
    private function getJson(string $url): array
    {
        $ch = curl_init($url);
        $ua = 'Smartresponsor-Locator/1.0 (+https://example.local)';
        curl_setopt_array($ch, [
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_TIMEOUT => $this->timeout,
          CURLOPT_HTTPHEADER => [
            'Accept: application/json',
            'User-Agent: ' . $ua,
          ],
        ]);
        $body = curl_exec($ch);
        if ($body === false) {
            $err = curl_error($ch);
            curl_close($ch);
            throw new \RuntimeException('HTTP request failed: ' . $err);
        }
        $code = (int)curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($code < 200 || $code >= 300) {
            throw new \RuntimeException('HTTP status ' . $code);
        }
        $data = json_decode((string)$body, true);
        if (!is_array($data)) {
            throw new \RuntimeException('Invalid JSON from Nominatim');
        }
        return $data;
    }
}
