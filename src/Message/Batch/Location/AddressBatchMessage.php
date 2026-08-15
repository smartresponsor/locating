<?php

declare(strict_types=1);

/*
 * Copyright (c) 2025 Oleksandr Tishchenko / Marketing America Corp
 */

namespace App\Locating\Message\Batch\Location;

use App\Locating\MessageInterface\Batch\Location\AddressBatchMessageInterface;

final class AddressBatchMessage implements AddressBatchMessageInterface
{
    /**
     * @param array<string,mixed> $payload
     */
    public function __construct(
        private readonly string $jobId,
        private readonly array $payload,
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
