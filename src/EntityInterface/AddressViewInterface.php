<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface AddressViewInterface
{
    public function street(): string;

    public function city(): string;

    public function region(): string;

    public function postalCode(): string;

    public function countryCode(): string;

    /**
     * @return array<string, string>
     */
    public function toArray(): array;
}
