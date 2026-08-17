<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Address\Location;

use App\Locating\ServiceInterface\Address\Location\AddressCanonicalizerInterface;

final class AddressCanonicalizerService implements AddressCanonicalizerInterface
{
    /** @param array<string, mixed> $raw */
    public function normalize(array $raw, string $locale = 'en'): array
    {
        $string = static fn (mixed $value): string => is_string($value) ? $value : '';
        $postal = preg_replace('/\s+/', '', $string($raw['postal'] ?? null));
        $out = [
            'street' => self::norm($string($raw['street'] ?? null)),
            'city' => self::title($string($raw['city'] ?? null)),
            'state' => strtoupper($string($raw['state'] ?? null)),
            'postal' => is_string($postal) ? $postal : '',
            'country' => strtoupper($string($raw['country'] ?? null)),
            'locale' => $locale,
        ];

        return $out;
    }

    private static function norm(string $v): string
    {
        $v = trim($v);
        $normalized = preg_replace('/\s+/', ' ', $v);

        return is_string($normalized) ? $normalized : $v;
    }

    private static function title(string $v): string
    {
        $v = strtolower($v);

        $titled = preg_replace_callback('/\b([a-z])/', static fn (array $match): string => strtoupper($match[1]), $v);

        return is_string($titled) ? $titled : $v;
    }
}
