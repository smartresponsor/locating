<?php
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace SmartResponsor\Infrastructure\Locator\Http;

use SmartResponsor\Domain\Locator\Config\Env;
use SmartResponsor\Service\Locator\Address\AddressParseService;
use SmartResponsor\Service\Locator\Address\AddressStandardizeService;
use SmartResponsor\Service\Locator\Locator\LocatorService;
use SmartResponsor\Service\Locator\Provider\ProviderRouter;
use SmartResponsor\Infrastructure\Locator\Cache\RedisCache;

class Kernel
{
    public function handle(): void
    {
        header('Content-Type: application/json');
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = explode('?', $_SERVER['REQUEST_URI'] ?? '/', 2)[0];

        $env = new Env();
        $cache = new RedisCache($env->get('REDIS_URL', ''));
        $router = new ProviderRouter($env, $cache);
        $parse = new AddressParseService($env);
        $standard = new AddressStandardizeService($env);

        try {
            if ($method === 'POST' && $uri === '/parse') {
                $in = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
                $address = (string)($in['address'] ?? '');
                $locale = (string)($in['locale'] ?? 'en');
                $res = $parse->parse($address, $locale);
                http_response_code(200);
                echo json_encode($res);
                return;
            }
            if ($method === 'POST' && $uri === '/standardize') {
                $in = json_decode(file_get_contents('php://input') ?: '{}', true) ?: [];
                $res = $standard->standardize($in);
                http_response_code(200);
                echo json_encode($res);
                return;
            }
            if ($method === 'GET' && $uri === '/geocode') {
                $q = (string)($_GET['q'] ?? '');
                $country = (string)($_GET['country'] ?? '');
                $res = $router->geocode($q, $country);
                http_response_code(200);
                echo json_encode(['items' => $res]);
                return;
            }
            if ($method === 'GET' && $uri === '/reverse') {
                $lat = (float)($_GET['lat'] ?? 0);
                $lon = (float)($_GET['lon'] ?? 0);
                $res = $router->reverse($lat, $lon);
                http_response_code(200);
                echo json_encode(['items' => $res]);
                return;
            }
            if ($method === 'GET' && $uri === '/autocomplete') {
                $q = (string)($_GET['q'] ?? '');
                $country = (string)($_GET['country'] ?? '');
                $bbox = (string)($_GET['bbox'] ?? '');
                $res = $router->autocomplete($q, $country, $bbox);
                http_response_code(200);
                echo json_encode(['suggestions' => $res]);
                return;
            }
            if ($method === 'GET' && $uri === '/store/search') {
                $lat = isset($_GET['lat']) ? (float)$_GET['lat'] : null;
                $lon = isset($_GET['lon']) ? (float)$_GET['lon'] : null;
                $radius = isset($_GET['radiusMeters']) ? (int)$_GET['radiusMeters'] : 1000;
                $bbox = (string)($_GET['bbox'] ?? '');
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
