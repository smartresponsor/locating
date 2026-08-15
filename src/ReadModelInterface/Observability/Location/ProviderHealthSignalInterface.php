<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderHealthSignalInterface
{
    public function sourceKey(): string;

    public function successRate(): float;

    public function ewmaMs(): float;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
