<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 * Smartresponsor Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Service\Provider\Location\Runtime\Resilience;

use App\Locating\ServiceInterface\Provider\Location\Runtime\Resilience\FailoverMatrixInterface;

final class FailoverMatrix implements FailoverMatrixInterface
{
    /** @var array<string, array<string, array<string>>> map[region][primary] = [secondary,...] */
    private array $map = [];

    public function set(string $region, string $primary, array $fallback): void
    {
        $this->map[$region] = $this->map[$region] ?? [];
        $this->map[$region][$primary] = array_values(array_unique($fallback));
    }

    /** Return ordered candidates for region starting from primary, then fallback list */
    public function candidate(string $region, string $primary): array
    {
        $list = $this->map[$region][$primary] ?? [];

        return array_values(array_unique(array_merge([$primary], $list)));
    }
}
