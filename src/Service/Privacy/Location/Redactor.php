<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Privacy\Location;

final class Redactor
{
    /**
     * @param array<string,mixed> $event
     * @return array<string,mixed>
     */
    public function apply(array $event): array
    {
        foreach (['email','phone','street'] as $f) {
            if (isset($event[$f])) {
                $event[$f] = '<redacted>';
            }
        }
        return $event;
    }
}
