<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Address\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */
interface AddressHintBiasServiceInterface
{
    public function set(string $tag, string $region, float $weight): void;

    /** @param array<array-key, mixed> $hint */
    public function weight(array $hint, string $region): float;

    /** @param array<string, mixed> $hint */
    public function region(array $hint): string;

    /** @param array<string, mixed> $hint */
    public function locale(array $hint): string;
}
