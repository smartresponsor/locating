<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Experiment;

interface ShadowTrafficInterface
{
    /** @param list<string> $candidate */
    public function pick(string $primary, array $candidate, float $ratio): string;

    /**
     * @param array<string,mixed> $primaryResult
     * @param array<string,mixed> $shadowResult
     */
    public function record(string $primary, string $shadow, array $primaryResult, array $shadowResult): void;
}
