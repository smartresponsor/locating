<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Service\Locator;
final class Redactor {
    /** Simple field redactor */
    public function apply(array $event): array {
        foreach (['email','phone','street'] as $f) {
            if (isset($event[$f])) { $event[$f] = '<redacted>'; }
        }
        return $event;
    }
}
