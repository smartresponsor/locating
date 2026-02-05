<?php
declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace Smartresponsor\Domain\Locator;
interface BanditPolicyInterface {
    /** Select provider arm; return provider id. */
    public function select(array $arm): string;
    /** Update reward for provider arm. */
    public function update(string $armId, float $reward): void;
}
