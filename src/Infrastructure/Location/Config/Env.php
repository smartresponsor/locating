<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Owner: Marketing America Corp
 * Author: Oleksandr Tishchenko <dev@smartresponsor.com>
 */

namespace App\Locating\Infrastructure\Location\Config;

class Env
{
    public function get(string $key, string $default = ''): string
    {
        $v = getenv($key);
        if ($v === false) {
            $path = __DIR__ . '/../../../.env';
            if (is_file($path)) {
                $lines = file($path) ?: [];
                foreach ($lines as $line) {
                    if (preg_match('/^\s*#/', $line)) {
                        continue;
                    }
                    if (strpos($line, '=') !== false) {
                        [$k, $val] = array_map('trim', explode('=', $line, 2));
                        if ($k === $key) {
                            return $val;
                        }
                    }
                }
            }
            return $default;
        }
        return $v;
    }
}
