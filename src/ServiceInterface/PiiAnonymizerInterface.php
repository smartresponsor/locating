<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\ServiceInterface;

/**
 */

interface PiiAnonymizerInterface
{
    public function mask(array $row, array $map): array;
}