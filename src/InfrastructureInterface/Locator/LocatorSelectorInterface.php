<?php
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface LocatorSelectorInterface
{
    public function getActiveStrategy(): LocatorInterface;
}