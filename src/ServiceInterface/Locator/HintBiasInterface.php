<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface HintBiasInterface
{
    public function region(array $hint): string;
    public function locale(array $hint): string;
}