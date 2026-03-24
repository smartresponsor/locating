<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface ProviderCostSignalInterface
{
    public function sourceKey(): string;

    public function operation(): string;

    public function region(): string;

    public function unitCost(): float;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
