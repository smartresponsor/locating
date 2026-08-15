<?php

declare(strict_types=1);
/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 *  Canon: single-hyphen naming, mirror interfaces, singular names only.
 * Comments in English only. Postgres = Data, MySQL = Infrastructure.
 */

namespace App\Locating\Tests\Support\Locator;

use App\Locating\Tests\Support\Provider\Location\Interface\ProviderAdapterInterface;

final class FakeProvider implements ProviderAdapterInterface
{
    public function __construct(
        private string $nameEntity,
        private float $lat = 29.76,
        private float $lon = -95.37,
    ) {
    }

    public function call(array $request): array
    {
        $q = (string) ($request['q'] ?? 'unknown');

        return [
            'status' => 'ok',
            'q' => $q,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'text' => $this->nameEntity.' '.$q,
        ];
    }
}
