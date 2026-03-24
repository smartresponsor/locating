<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace App\EntityInterface;

use App\Entity\AddressData;

interface AddressSuggestionInterface
{
    public function label(): string;

    public function addressData(): AddressData;

    public function providerKey(): ?string;

    public function score(): ?float;

    /**
     * @return array<string,float>
     */
    public function rankReason(): array;

    /**
     * @param array<string,float> $reason
     */
    public function withScore(float $score, array $reason): self;
}
