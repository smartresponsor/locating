<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Service;
final class HintBias {
    /** Apply simple bias to provider/region choice given hints */
    public function region(array $hint): string {
        $country = strtoupper((string)($hint['country'] ?? ''));
        $region = match($country){
            'US' => 'us',
            'CA' => 'us',
            'GB','UK' => 'eu',
            'DE','FR','ES','IT','NL' => 'eu',
            'AU','NZ' => 'apac',
            default => 'us',
        };
        return (string)($hint['region'] ?? $region);
    }
    public function locale(array $hint): string {
        $loc = (string)($hint['locale'] ?? '');
        if ($loc !== '') { return $loc; }
        $country = strtoupper((string)($hint['country'] ?? 'US'));
        return $country === 'US' ? 'en' : 'en';
    }
}
