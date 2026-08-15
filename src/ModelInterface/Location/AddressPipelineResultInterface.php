<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\ModelInterface\Location;

interface AddressPipelineResultInterface
{
    public function status(): string;

    public function address(): ?AddressViewInterface;

    /**
     * @return list<AddressIssueInterface>
     */
    public function issues(): array;

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array;
}
