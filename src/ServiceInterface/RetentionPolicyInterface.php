<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain;
interface RetentionPolicyInterface {
    /** Set ttl days for entity name. */
    public function set(string $entity, int $ttlDay): void;
    /** Return ttl days for entity with fallback. */
    public function ttl(string $entity): int;
}
