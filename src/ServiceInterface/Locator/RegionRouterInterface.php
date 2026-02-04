<?php
declare(strict_types=1);

namespace App\ServiceInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface RegionRouterInterface
{
    public function select(array $regionHealth, array $slaWeight): string;
}