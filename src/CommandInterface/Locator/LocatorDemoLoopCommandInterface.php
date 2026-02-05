<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\CommandInterface\Locator;

interface LocatorDemoLoopCommandInterface
{
    public function runLoop(string $tenantId, int $roundMax, int $sleepSecond): int;
}
