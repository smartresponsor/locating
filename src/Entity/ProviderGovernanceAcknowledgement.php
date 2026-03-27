<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\ProviderGovernanceAcknowledgementInterface;

final class ProviderGovernanceAcknowledgement implements ProviderGovernanceAcknowledgementInterface
{
    public function __construct(
        private readonly string $sourceKey,
        private readonly string $stepCode,
        private readonly string $requestedOutcome,
        private readonly string $normalizedOutcome,
        private readonly string $acknowledgementState,
        private readonly bool $accepted,
        private readonly string $note,
    ) {
    }

    public function sourceKey(): string
    {
        return $this->sourceKey;
    }

    public function stepCode(): string
    {
        return $this->stepCode;
    }

    public function requestedOutcome(): string
    {
        return $this->requestedOutcome;
    }

    public function normalizedOutcome(): string
    {
        return $this->normalizedOutcome;
    }

    public function acknowledgementState(): string
    {
        return $this->acknowledgementState;
    }

    public function accepted(): bool
    {
        return $this->accepted;
    }

    public function note(): string
    {
        return $this->note;
    }

    public function toArray(): array
    {
        return [
            'sourceKey' => $this->sourceKey,
            'stepCode' => $this->stepCode,
            'requestedOutcome' => $this->requestedOutcome,
            'normalizedOutcome' => $this->normalizedOutcome,
            'acknowledgementState' => $this->acknowledgementState,
            'accepted' => $this->accepted,
            'note' => $this->note,
        ];
    }
}
