<?php

declare(strict_types=1);

require dirname(__DIR__, 3).'/vendor/autoload.php';

use App\Locating\Infrastructure\Location\Config\Env;
use App\Locating\Infrastructure\Provider\Location\Security\ApiKeyAuth;

$apiKey = $argv[1] ?? '-';
$header = $argv[2] ?? '-';

if ('-' === $apiKey) {
    putenv('API_KEY');
} else {
    putenv('API_KEY='.$apiKey);
}

unset($_SERVER['HTTP_X_API_KEY']);

if ('-' !== $header) {
    $_SERVER['HTTP_X_API_KEY'] = $header;
}

ApiKeyAuth::assert(new Env());

echo 'ok';
