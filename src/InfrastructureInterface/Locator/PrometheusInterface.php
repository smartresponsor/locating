<?php
declare(strict_types=1);

namespace Smartresponsor\InfrastructureInterface\Locator;

/**
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

interface PrometheusInterface
{
    public function inc(string $name, float $v=1.0): void;
    public function render(array $series): string;
}