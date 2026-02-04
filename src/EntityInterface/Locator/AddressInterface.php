<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\EntityInterface\Locator;
interface AddressInterface {
    /** Return canonical normalized address fields. */
    public function normalize(array $raw, string $locale='en'): array;
}
