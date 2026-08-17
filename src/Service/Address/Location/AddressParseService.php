<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\Infrastructure\Location\Config\Env;
use App\Locating\Infrastructure\Provider\Location\Http\HttpClient;
use App\Locating\ServiceInterface\Address\Location\AddressParseServiceInterface;

class AddressParseService implements AddressParseServiceInterface
{
    private Env $env;
    private HttpClient $http;

    public function __construct(Env $env)
    {
        $this->env = $env;
        $this->http = new HttpClient();
    }

    /** @return array<string,mixed> */
    public function parse(string $address, string $locale): array
    {
        $libpostal = $this->env->get('LIBPOSTAL_URL', '');
        if ($libpostal) {
            [$code, $body] = $this->http->postJson(rtrim($libpostal, '/').'/parse', [
                'address' => $address, 'locale' => $locale,
            ]);
            if (200 === $code) {
                $data = json_decode($body, true);
                if (is_array($data)) {
                    $result = [];
                    foreach ($data as $key => $value) {
                        if (is_string($key)) {
                            $result[$key] = $value;
                        }
                    }

                    return $result;
                }
            }
        }
        $parts = preg_split('/,|\n/', $address) ?: [];
        $components = [];
        if (count($parts) > 0) {
            $components['line1'] = trim($parts[0]);
        }
        if (count($parts) > 1) {
            $components['line2'] = trim($parts[1]);
        }
        $confidence = 0.4;

        return ['components' => $components, 'confidence' => $confidence];
    }
}
