<?php

declare(strict_types=1);

namespace App\Locating\ServiceInterface\Address\Location;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */
interface AddressHintBiasServiceInterface
{
    public function region(array $hint): string;

    public function locale(array $hint): string;
}
