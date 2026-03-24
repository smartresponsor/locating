<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface;

/**
 */

interface NormalizerInterface
{
    public function canonicalize(array $raw, string $provider): array;
}