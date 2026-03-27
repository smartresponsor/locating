<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Entity\Location;

use App\EntityInterface\Location\ProviderQuotaSignalInterface;

final class ProviderQuotaSignal implements ProviderQuotaSignalInterface
{
    public function __construct(
        private readonly string $sourceKey,
        private readonly string $operation,
        private readonly bool $allowed,
    ) {
    }

    public function sourceKey(): string
    {
        return $this->sourceKey;
    }

    public function operation(): string
    {
        return $this->operation;
    }

    public function allowed(): bool
    {
        return $this->allowed;
    }

    public function toArray(): array
    {
        return [
            'sourceKey' => $this->sourceKey,
            'operation' => $this->operation,
            'allowed' => $this->allowed,
        ];
    }
}
