<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
if (!is_string($requestUri) || $requestUri === '') {
    $requestUri = '/';
}

$path = parse_url($requestUri, PHP_URL_PATH) ?: '/';
/** @var array<string, mixed> $query */
$query = $_GET;

header('Content-Type: application/json');

[$statusCode, $payload] = match ($path) {
    '/', '/index.php' => [200, rootPayload()],
    '/locator/status' => [200, statusPayload()],
    '/locator/address/suggest' => [200, suggestPayload($query)],
    '/locator/address/reverse' => reverseResponse($query),
    default => [404, ['error' => 'Not found']],
};

http_response_code($statusCode);
echo json_encode($payload);

return true;

/**
 * @return array{status:string,component:string,time:string}
 */
function rootPayload(): array
{
    return [
        'status' => 'ok',
        'component' => 'locator-sketch30',
        'time' => gmdate(DATE_ATOM),
    ];
}

/**
 * @return array{service:string,status:string,metrics:array<int, mixed>}
 */
function statusPayload(): array
{
    return [
        'service' => 'locator',
        'status' => 'ok',
        'metrics' => [],
    ];
}

/**
 * @param array<string, mixed> $query
 * @return array{items:list<array{label:string,address:array<string,string>,score:float,providerKey:string}>}
 */
function suggestPayload(array $query): array
{
    $queryText = trim(stringQueryValue($query, 'query'));
    if ($queryText === '') {
        return ['items' => []];
    }

    return [
        'items' => [[
            'label' => $queryText,
            'address' => [
                'street' => $queryText,
                'city' => '',
                'region' => '',
                'postalCode' => '',
                'countryCode' => strtoupper(stringQueryValue($query, 'country')),
            ],
            'score' => 1.0,
            'providerKey' => 'local-fixture',
        ]],
    ];
}

/**
 * @param array<string, mixed> $query
 * @return array{0:int,1:array<string, mixed>}
 */
function reverseResponse(array $query): array
{
    $latitude = floatQueryValue($query, 'lat');
    $longitude = floatQueryValue($query, 'lon');

    if (
        $latitude === null || $longitude === null
        || !is_finite($latitude) || !is_finite($longitude)
        || $latitude < -90 || $latitude > 90
        || $longitude < -180 || $longitude > 180
    ) {
        return [400, ['error' => 'lat/lon are out of bounds']];
    }

    return [200, [
        'status' => 'verified',
        'address' => [
            'street' => 'Fixture Street',
            'city' => 'Fixture City',
            'region' => 'Fixture Region',
            'postalCode' => '00000',
            'countryCode' => strtoupper(stringQueryValue($query, 'country', 'US')),
        ],
        'issues' => [],
        'geoPoint' => [
            'latitude' => $latitude,
            'longitude' => $longitude,
        ],
        'providerKey' => 'local-fixture',
    ]];
}

/**
 * @param array<string, mixed> $query
 */
function stringQueryValue(array $query, string $key, string $default = ''): string
{
    $value = $query[$key] ?? $default;

    return is_string($value) ? $value : $default;
}

/**
 * @param array<string, mixed> $query
 */
function floatQueryValue(array $query, string $key): ?float
{
    $value = $query[$key] ?? null;

    return is_numeric($value) ? (float) $value : null;
}
