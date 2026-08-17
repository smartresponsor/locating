<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\ServiceInterface\Provider\Location\Runtime\Batch;

interface BatchGeocodeEndpointInterface
{
    /**
     * @param list<string|array{text?:string}> $input
     * @return array<int, array<string, mixed>>
     */
    public function handle(array $input): array;
}
