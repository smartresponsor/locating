<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

namespace App\Locating\Infrastructure\Provider\Location\Http;

use App\Locating\Infrastructure\Location\Config\Env;
use App\Locating\Infrastructure\Provider\Location\Cache\RedisCache;
use App\Locating\Service\Address\Location\AddressParseService;
use App\Locating\Service\Address\Location\AddressStandardizeService;
use App\Locating\Service\Provider\Location\ProviderRouterService;
use App\Locating\Service\Provider\Location\Runtime\Geo\LocatorService;

class Kernel
{
    public function handle(): void
    {
        header('Content-Type: application/json');
        $methodValue = $_SERVER['REQUEST_METHOD'] ?? null;
        $method = is_string($methodValue) ? $methodValue : 'GET';
        $uriValue = $_SERVER['REQUEST_URI'] ?? null;
        $uri = explode('?', is_string($uriValue) ? $uriValue : '/', 2)[0];

        $env = new Env();
        $cache = new RedisCache($env->get('REDIS_URL', ''));
        $router = new ProviderRouterService($env, $cache);
        $parse = new AddressParseService($env);
        $standard = new AddressStandardizeService($env);
        $queryString = static function (string $key): string {
            $value = $_GET[$key] ?? null;

            return is_string($value) ? $value : '';
        };
        $queryFloat = static function (string $key): ?float {
            $value = $_GET[$key] ?? null;

            return is_numeric($value) ? (float) $value : null;
        };
        $queryInt = static function (string $key): ?int {
            $value = $_GET[$key] ?? null;

            return is_numeric($value) ? (int) $value : null;
        };

        try {
            if ('POST' === $method && '/parse' === $uri) {
                $decoded = json_decode(file_get_contents('php://input') ?: '{}', true);
                $in = is_array($decoded) ? $decoded : [];
                $address = is_string($in['address'] ?? null) ? $in['address'] : '';
                $locale = is_string($in['locale'] ?? null) ? $in['locale'] : 'en';
                $res = $parse->parse($address, $locale);
                http_response_code(200);
                echo json_encode($res);

                return;
            }
            if ('POST' === $method && '/standardize' === $uri) {
                $decoded = json_decode(file_get_contents('php://input') ?: '{}', true);
                $in = [];
                if (is_array($decoded)) {
                    foreach ($decoded as $key => $value) {
                        if (is_string($key)) {
                            $in[$key] = $value;
                        }
                    }
                }
                $res = $standard->standardize($in);
                http_response_code(200);
                echo json_encode($res);

                return;
            }
            if ('GET' === $method && '/geocode' === $uri) {
                $q = $queryString('q');
                $country = $queryString('country');
                $res = $router->geocode($q, $country);
                http_response_code(200);
                echo json_encode(['items' => $res]);

                return;
            }
            if ('GET' === $method && '/reverse' === $uri) {
                $lat = $queryFloat('lat') ?? 0.0;
                $lon = $queryFloat('lon') ?? 0.0;
                $res = $router->reverse($lat, $lon);
                http_response_code(200);
                echo json_encode(['items' => $res]);

                return;
            }
            if ('GET' === $method && '/autocomplete' === $uri) {
                $q = $queryString('q');
                $country = $queryString('country');
                $bbox = $queryString('bbox');
                $res = $router->autocomplete($q, $country, $bbox);
                http_response_code(200);
                echo json_encode(['suggestions' => $res]);

                return;
            }
            if ('GET' === $method && '/store/search' === $uri) {
                $lat = $queryFloat('lat');
                $lon = $queryFloat('lon');
                $radius = $queryInt('radiusMeters') ?? 1000;
                $bbox = $queryString('bbox');
                $service = new LocatorService($cache);
                $res = $service->search($lat, $lon, $radius, $bbox);
                http_response_code(200);
                echo json_encode(['items' => $res]);

                return;
            }
            http_response_code(404);
            echo json_encode(['error' => 'Not found']);
        } catch (\Throwable $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Server error', 'detail' => $e->getMessage()]);
        }
    }
}
