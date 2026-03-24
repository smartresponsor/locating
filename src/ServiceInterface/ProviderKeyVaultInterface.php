<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\LayerInterface\Domaine;
interface ProviderKeyVaultInterface {
    public function add(string $providerId, string $keyId, string $secret, int $priority, int $tsStart, int $tsEnd): void;
    public function current(string $providerId): ?string;
    public function rotate(string $providerId): ?string;
}
