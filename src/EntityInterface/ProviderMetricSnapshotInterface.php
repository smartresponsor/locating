<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface ProviderMetricSnapshotInterface
{
    public function operation(): string;

    public function count(): int;

    public function errorCount(): int;

    public function avgMs(): float;

    public function errorRate(): float;

    /**
     * @return array{count:int,errorCount:int,avgMs:float,errorRate:float}
     */
    public function toArray(): array;
}
