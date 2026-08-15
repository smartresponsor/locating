<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Privacy;

interface RetentionPolicyInterface
{
    /** Set ttl days for entity nameEntity. */
    public function set(string $entity, int $ttlDay): void;
    /** Return ttl days for entity with fallback. */
    public function ttl(string $entity): int;
}
