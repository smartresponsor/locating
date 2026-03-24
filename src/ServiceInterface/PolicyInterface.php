<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\ServiceInterface;

/**
 */

interface PolicyInterface
{
    public function order(string $purpose, ?string $region): array;
}