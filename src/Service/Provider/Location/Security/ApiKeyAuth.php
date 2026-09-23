<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>.
 */

namespace App\Locating\Service\Provider\Location\Security;

use App\Locating\Service\Location\Config\Env;

class ApiKeyAuth
{
    public static function assert(Env $env): void
    {
        $key = trim($env->get('API_KEY', ''));
        if ('' === $key) {
            return;
        }
        $headerValue = $_SERVER['HTTP_X_API_KEY'] ?? null;
        $header = is_string($headerValue) ? $headerValue : '';
        if (!hash_equals($key, $header)) {
            http_response_code(401);
            echo json_encode(['error' => 'unauthorized']);
            exit;
        }
    }
}
