<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\ServiceInterface;

interface LocatorDemoLoopInterface
{
    public function runLoop(string $tenantId, int $roundMax, int $sleepSecond): void;
}
