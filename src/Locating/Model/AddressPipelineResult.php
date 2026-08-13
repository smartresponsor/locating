<?php

declare(strict_types=1);

namespace App\Locating\Model;

final readonly class AddressPipelineResult
{
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_PARTIAL = 'partial';
    public const STATUS_REJECTED = 'rejected';

    /** @param list<array{field:string,code:string,message:string}> $issues */
    public function __construct(
        private string $status,
        private ?AddressView $address,
        private array $issues,
    ) {
    }

    public function status(): string { return $this->status; }
    public function address(): ?AddressView { return $this->address; }

    /** @return list<array{field:string,code:string,message:string}> */
    public function issues(): array
    {
        return $this->issues;
    }
}