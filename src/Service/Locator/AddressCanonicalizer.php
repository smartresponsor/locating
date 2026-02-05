<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service\Locator;
final class AddressCanonicalizer implements AddressInterface {
    public function normalize(array $raw, string $locale='en'): array {
        $out = [
            'street' => self::norm((string)($raw['street'] ?? '')),
            'city'   => self::title((string)($raw['city'] ?? '')),
            'state'  => strtoupper((string)($raw['state'] ?? '')),
            'postal' => preg_replace('/\s+/', '', (string)($raw['postal'] ?? '')),
            'country'=> strtoupper((string)($raw['country'] ?? '')),
            'locale' => $locale,
        ];
        return $out;
    }
    private static function norm(string $v): string {
        $v = trim($v);
        $v = preg_replace('/\s+/', ' ', $v);
        return $v;
    }
    private static function title(string $v): string {
        $v = strtolower($v);
        return preg_replace_callback('/\b([a-z])/', fn($m)=>strtoupper($m[1]), $v);
    }
}
