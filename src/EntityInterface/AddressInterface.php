<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\EntityInterface;
interface AddressInterface {
    /** Return canonical normalized address fields. */
    public function normalize(array $raw, string $locale='en'): array;
}
