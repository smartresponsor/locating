<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\EntityInterface\Location;

interface AddressIssueInterface
{
    public function field(): string;

    public function code(): string;

    public function message(): string;

    /**
     * @return array{field:string,code:string,message:string}
     */
    public function toArray(): array;
}
