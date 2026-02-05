<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface ProviderContractVersionInterface {
    /** Register supported version for provider/op. */
    public function register(string $providerId, string $op, string $version): void;
    /** Return selected version for request (explicit or latest). */
    public function select(string $providerId, string $op, ?string $want): string;
    /** Return true if version is supported. */
    public function support(string $providerId, string $op, string $version): bool;
}
