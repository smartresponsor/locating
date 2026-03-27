<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
if (!is_string($requestUri) || $requestUri === '') {
    $requestUri = '/';
}

$path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
$query = $_GET;

header('Content-Type: application/json');

if ($path === '/' || $path === '/index.php') {
    http_response_code(200);
    echo json_encode([
        'status' => 'ok',
        'component' => 'locator-sketch30',
        'time' => gmdate(DATE_ATOM),
    ]);

    return true;
}

if ($path === '/locator/status') {
    http_response_code(200);
    echo json_encode([
        'service' => 'locator',
        'status' => 'ok',
        'metrics' => [],
    ]);

    return true;
}

if ($path === '/locator/address/suggest') {
    http_response_code(200);
    $queryParam = $query['query'] ?? '';
    $queryText = trim(is_string($queryParam) ? $queryParam : '');

    $items = $queryText === '' ? [] : [[
        'label' => $queryText,
        'address' => [
            'street' => $queryText,
            'city' => '',
            'region' => '',
            'postalCode' => '',
            'countryCode' => strtoupper(is_string($query['country'] ?? null) ? $query['country'] : ''),
        ],
        'score' => 1.0,
        'providerKey' => 'local-fixture',
    ]];

    echo json_encode(['items' => $items]);

    return true;
}

if ($path === '/locator/address/reverse') {
    $latRaw = $query['lat'] ?? null;
    $lonRaw = $query['lon'] ?? null;
    $lat = is_numeric($latRaw) ? (float)$latRaw : null;
    $lon = is_numeric($lonRaw) ? (float)$lonRaw : null;

    if ($lat === null || $lon === null || !is_finite($lat) || !is_finite($lon) || $lat < -90 || $lat > 90 || $lon < -180 || $lon > 180) {
        http_response_code(400);
        echo json_encode(['error' => 'lat/lon are out of bounds']);

        return true;
    }

    http_response_code(200);
    echo json_encode([
        'status' => 'verified',
        'address' => [
            'street' => 'Fixture Street',
            'city' => 'Fixture City',
            'region' => 'Fixture Region',
            'postalCode' => '00000',
            'countryCode' => strtoupper(is_string($query['country'] ?? null) ? $query['country'] : 'US'),
        ],
        'issues' => [],
        'geoPoint' => [
            'latitude' => $lat,
            'longitude' => $lon,
        ],
        'providerKey' => 'local-fixture',
    ]);

    return true;
}

http_response_code(404);
echo json_encode(['error' => 'Not found']);

return true;
