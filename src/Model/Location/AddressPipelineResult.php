<?php

declare(strict_types=1);

/*
 * Marketing America Corp. Oleksandr Tishchenko
 * dev@highhopesamerica.com
 */

namespace App\Locating\Model\Location;

use App\Locating\ModelInterface\Location\AddressIssueInterface;
use App\Locating\ModelInterface\Location\AddressPipelineResultInterface;
use App\Locating\ModelInterface\Location\AddressViewInterface;

final class AddressPipelineResult implements AddressPipelineResultInterface
{
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_AMBIGUOUS = 'ambiguous';

    /**
     * @param list<AddressIssueInterface> $issues
     */
    public function __construct(
        private readonly string $status,
        private readonly ?AddressViewInterface $address,
        private readonly array $issues,
    ) {
    }

    public function status(): string
    {
        return $this->status;
    }

    public function address(): ?AddressViewInterface
    {
        return $this->address;
    }

    public function issues(): array
    {
        return $this->issues;
    }

    public function toArray(): array
    {
        return [
            'status' => $this->status,
            'address' => $this->address?->toArray(),
            'issues' => array_map(
                static fn (AddressIssueInterface $issue): array => $issue->toArray(),
                $this->issues,
            ),
        ];
    }
}
