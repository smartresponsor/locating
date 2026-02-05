<?php
declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace Smartresponsor\InfrastructureInterface\Locator;

interface MetricSnapshotProviderInterface
{
    /**
     * @return array<string,array<string,float|int>>
     */
    public function snapshot(): array;
}
