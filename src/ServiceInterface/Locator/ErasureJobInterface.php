<?php
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface ErasureJobInterface
{
    public function plan(string $kind, array $row, bool $legalHold=false): array;
}