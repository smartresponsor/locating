<?php

declare(strict_types=1);

namespace App\Locating\Model\Location\Batch;

final class AddressBatchMessage
{
    /**
     * @param array<string,mixed> $payload
     */
    public function __construct(
        private string $jobId,
        private array $payload,
    ) {
    }

    public function jobId(): string
    {
        return $this->jobId;
    }

    /**
     * @return array<string,mixed>
     */
    public function payload(): array
    {
        return $this->payload;
    }
}
