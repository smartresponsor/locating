<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface HintBiasInterface
{
    public function region(array $hint): string;
    public function locale(array $hint): string;
}