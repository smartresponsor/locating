<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);
/**
 * App Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Domain;
interface BanditPolicyInterface {
    /** Select provider arm; return provider id. */
    public function select(array $arm): string;
    /** Update reward for provider arm. */
    public function update(string $armId, float $reward): void;
}
