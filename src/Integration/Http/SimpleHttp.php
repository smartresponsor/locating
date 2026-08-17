<?php

declare(strict_types=1);

namespace App\Locating\Integration\Http;

final class SimpleHttp
{
    /** @return array<string,mixed> */
    public static function get(string $url, int $timeout = 10): array
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => $timeout]);
        $body = curl_exec($ch);
        $code = (int) curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
        curl_close($ch);
        if ($body === false || $code < 200 || $code >= 300) {
            return [];
        }
        $decoded = json_decode((string) $body, true);
        if (!is_array($decoded)) {
            return [];
        }
        $result = [];
        foreach ($decoded as $key => $value) {
            if (is_string($key)) {
                $result[$key] = $value;
            }
        }

        return $result;
    }
}
