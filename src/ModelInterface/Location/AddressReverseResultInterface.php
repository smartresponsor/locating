<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ModelInterface\Location;

interface AddressReverseResultInterface
{
    public function status(): string;

    public function address(): ?AddressViewInterface;

    /**
     * @return list<array{field:string, code:string, message:string}>
     */
    public function issues(): array;

    /**
     * @return array{latitude: float, longitude: float}|null
     */
    public function geoPoint(): ?array;

    public function providerKey(): ?string;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
