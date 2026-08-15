<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ReadModelInterface\Observability\Location;

interface ProviderQuotaSignalInterface
{
    public function sourceKey(): string;

    public function operation(): string;

    public function allowed(): bool;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
