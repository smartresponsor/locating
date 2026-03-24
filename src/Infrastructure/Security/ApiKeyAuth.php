<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
/**
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */


namespace Smartresponsor\Infrastructure\Security;
use Smartresponsor\Domain\Config\Env;
class ApiKeyAuth {
    public static function assert(Env $env): void {
        $key = trim($env->get('API_KEY',''));
        if($key==='') return;
        $hdr = $_SERVER['HTTP_X_API_KEY'] ?? '';
        if(!hash_equals($key, (string)$hdr)){
            http_response_code(401);
            echo json_encode(['error'=>'unauthorized']);
            exit;
        }
    }
}
