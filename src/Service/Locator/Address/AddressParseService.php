<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace SmartResponsor\Service\Locator\Address;

use SmartResponsor\Domain\Locator\Config\Env;
use SmartResponsor\Infrastructure\Locator\Http\HttpClient;
use SmartResponsor\ServiceInterface\Locator\Address\AddressParseServiceInterface;

class AddressParseService implements AddressParseServiceInterface
{
    private Env $env;
    private HttpClient $http;
    public function __construct(Env $env)
    {
        $this->env = $env;
        $this->http = new HttpClient();
    }

    public function parse(string $address, string $locale): array
    {
        $libpostal = $this->env->get('LIBPOSTAL_URL', '');
        if ($libpostal) {
            [$code, $body] = $this->http->postJson(rtrim($libpostal, '/') . '/parse', [
                'address' => $address, 'locale' => $locale
            ]);
            if ($code === 200) {
                $data = json_decode($body, true);
                if (is_array($data)) return $data;
            }
        }
        $parts = preg_split('/,|\n/', $address);
        $components = [];
        if (count($parts) > 0) $components['line1'] = trim($parts[0]);
        if (count($parts) > 1) $components['line2'] = trim($parts[1]);
        $confidence = 0.4;
        return ['components' => $components, 'confidence' => $confidence];
    }
}
