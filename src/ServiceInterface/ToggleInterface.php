<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\LayerInterface\Domaine;
interface ToggleInterface {
    public function isEnabled(string $key, string $tenantId='', int $seed=0): bool;
    public function set(string $key, bool $enabled, int $percent=100, string $tenantId=''): void;
}
