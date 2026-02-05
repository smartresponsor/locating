<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\ServiceInterface\Locator;

interface RegionTimeoutPolicyInterface
{
    public function add(string $providerId, string $region, string $op, int $timeoutMs): void;

    public function timeout(string $providerId, string $region, string $op, int $defaultMs): int;
}
