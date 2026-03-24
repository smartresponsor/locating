<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface ErasureJobInterface
{
    public function plan(string $kind, array $row, bool $legalHold=false): array;
}