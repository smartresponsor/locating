<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\LayerInterface\Domaine;
interface KeyStoreInterface {
    public function get(string $providerId): ?string;
    public function set(string $providerId, string $key, ?int $startAtTs=null, ?int $revokeAtTs=null): void;
}
