<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\CommandInterface;

interface LocatorDemoLoopCommandInterface
{
    public function runLoop(string $tenantId, int $roundMax, int $sleepSecond): int;
}
