<?php
# Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
declare(strict_types=1);

namespace Smartresponsor\Entity;

use Smartresponsor\EntityInterface\AddressSuggestionInterface;

final class AddressSuggestion implements AddressSuggestionInterface
{
    public function __construct(
        private string $label,
        private AddressData $addressData,
        private ?string $providerKey = null,
        private ?float $score = null,
        private array $rankReason = []
    ) {
    }

    public function label(): string
    {
        return $this->label;
    }

    public function addressData(): AddressData
    {
        return $this->addressData;
    }

    public function providerKey(): ?string
    {
        return $this->providerKey;
    }

    public function score(): ?float
    {
        return $this->score;
    }

    public function rankReason(): array
    {
        return $this->rankReason;
    }

    public function withScore(float $score, array $reason): self
    {
        $clone = clone $this;
        $clone->score = $score;
        $clone->rankReason = $reason;

        return $clone;
    }

    public function toArray(): array
    {
        return [
            'label' => $this->label,
            'address' => $this->addressData->toArray(),
            'providerKey' => $this->providerKey,
            'score' => $this->score,
            'rankReason' => $this->rankReason,
        ];
    }
}
