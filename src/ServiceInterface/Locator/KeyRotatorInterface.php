<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * SmartResponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain\Locator;
interface KeyRotatorInterface {
    /** Return active key for provider as of now; rotate if current is near revoke. */
    public function active(string $providerId, int $nowTs=null): ?string;
    /** Register key with start/revoke timestamps. */
    public function register(string $providerId, string $key, int $startAtTs, int $revokeAtTs): void;
}
