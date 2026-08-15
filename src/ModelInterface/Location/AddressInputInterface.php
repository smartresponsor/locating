<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ModelInterface\Location;

interface AddressInputInterface
{
    public function raw(): string;

    /**
     * @return array<string, mixed>
     */
    public function data(): array;

    /**
     * @return array{raw:string,data:array<string,mixed>}
     */
    public function toArray(): array;
}
