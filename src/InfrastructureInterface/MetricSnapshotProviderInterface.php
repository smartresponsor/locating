<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);


namespace App\InfrastructureInterface;

interface MetricSnapshotProviderInterface
{
    /**
     * @return array<string,array<string,float|int>>
     */
    public function snapshot(): array;
}
